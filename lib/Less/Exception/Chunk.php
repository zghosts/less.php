<?php

/**
 * Chunk Exception
 *
 * @package    Less
 * @subpackage exception
 */
class Less_Exception_Chunk extends Less_Exception_Parser
{


    protected $parserCurrentIndex = 0;

    protected $emitFrom = 0;

    protected $inputLength;


    /**
     * Constructor
     *
     * @param string               $input
     * @param Exception            $previous    Previous exception
     * @param integer              $index       The current parser index
     * @param Less_FileInfo|string $currentFile The file
     * @param integer              $code        The exception code
     */
    public function __construct($input, Exception $previous = null, $index = null, $currentFile = null, $code = 0)
    {

        $this->message = 'ParseError: Unexpected input'; //default message

        $this->index = $index;

        $this->currentFile = $currentFile;

        $this->input       = $input;
        $this->inputLength = strlen($input);

        $this->chunks();
        $this->genMessage();
    }


    /**
     * See less.js chunks()
     * We don't actually need the chunks
     *
     */
    protected function chunks()
    {
        $level                    = 0;
        $parenLevel               = 0;
        $lastMultiCommentEndBrace = null;
        $lastOpening              = null;
        $lastMultiComment         = null;
        $lastParen                = null;

        for ($this->parserCurrentIndex = 0; $this->parserCurrentIndex < $this->inputLength; $this->parserCurrentIndex++) {
            $charCode = $this->charCode($this->parserCurrentIndex);
            if ((($charCode >= 97) && ($charCode <= 122)) || ($charCode < 34)) {
                // a-z or whitespace
                continue;
            }

            switch ($charCode) {

                // (
                case 40:
                    $parenLevel++;
                    $lastParen = $this->parserCurrentIndex;
                    continue;

                // )
                case 41:
                    $parenLevel--;
                    if ($parenLevel < 0) {
                        return $this->fail("missing opening `(`");
                    }
                    continue;

                // ;
                case 59:
                    //if (!$parenLevel) { $this->emitChunk();	}
                    continue;

                // {
                case 123:
                    $level++;
                    $lastOpening = $this->parserCurrentIndex;
                    continue;

                // }
                case 125:
                    $level--;
                    if ($level < 0) {
                        return $this->fail("missing opening `{`");

                    }
                    //if (!$level && !$parenLevel) { $this->emitChunk(); }
                    continue;
                // \
                case 92:
                    if ($this->parserCurrentIndex < $this->inputLength - 1) {
                        $this->parserCurrentIndex++;
                        continue;
                    }
                    return $this->fail("unescaped `\\`");

                // ", ' and `
                case 34:
                case 39:
                case 96:
                    $matched                = 0;
                    $currentChunkStartIndex = $this->parserCurrentIndex;
                    for ($this->parserCurrentIndex = $this->parserCurrentIndex + 1; $this->parserCurrentIndex < $this->inputLength; $this->parserCurrentIndex++) {
                        $nestedCharCode = $this->charCode($this->parserCurrentIndex);
                        if ($nestedCharCode > 96) {
                            continue;
                        }
                        if ($nestedCharCode == $charCode) {
                            $matched = 1;
                            break;
                        }
                        if ($nestedCharCode == 92) { // \
                            if ($this->parserCurrentIndex == $this->inputLength - 1) {
                                return $this->fail("unescaped `\\`");
                            }
                            $this->parserCurrentIndex++;
                        }
                    }
                    if ($matched) {
                        continue;
                    }
                    return $this->fail("unmatched `" . chr($charCode) . "`", $currentChunkStartIndex);

                // /, check for comment
                case 47:
                    if ($parenLevel || ($this->parserCurrentIndex == $this->inputLength - 1)) {
                        continue;
                    }
                    $nestedCharCode = $this->charCode($this->parserCurrentIndex + 1);
                    if ($nestedCharCode == 47) {
                        // //, find lnfeed
                        for ($this->parserCurrentIndex = $this->parserCurrentIndex + 2; $this->parserCurrentIndex < $this->inputLength; $this->parserCurrentIndex++) {
                            $nestedCharCode = $this->charCode($this->parserCurrentIndex);
                            if (($nestedCharCode <= 13) && (($nestedCharCode == 10) || ($nestedCharCode == 13))) {
                                break;
                            }
                        }
                    } else {
                        if ($nestedCharCode == 42) {
                            // /*, find */
                            $lastMultiComment = $currentChunkStartIndex = $this->parserCurrentIndex;
                            for ($this->parserCurrentIndex = $this->parserCurrentIndex + 2; $this->parserCurrentIndex < $this->inputLength - 1; $this->parserCurrentIndex++) {
                                $nestedCharCode = $this->charCode($this->parserCurrentIndex);
                                if ($nestedCharCode == 125) {
                                    $lastMultiCommentEndBrace = $this->parserCurrentIndex;
                                }
                                if ($nestedCharCode != 42) {
                                    continue;
                                }
                                if ($this->charCode($this->parserCurrentIndex + 1) == 47) {
                                    break;
                                }
                            }
                            if ($this->parserCurrentIndex == $this->inputLength - 1) {
                                return $this->fail("missing closing `*/`", $currentChunkStartIndex);
                            }
                        }
                    }
                    continue;

                // *, check for unmatched */
                case 42:
                    if (($this->parserCurrentIndex < $this->inputLength - 1) &&
                        ($this->charCode($this->parserCurrentIndex + 1) == 47)
                    ) {
                        return $this->fail("unmatched `/*`");
                    }
                    continue;
            }
        }

        if ($level !== 0) {
            if (($lastMultiComment > $lastOpening) && ($lastMultiCommentEndBrace > $lastMultiComment)) {
                return $this->fail("missing closing `}` or `*/`", $lastOpening);
            } else {
                return $this->fail("missing closing `}`", $lastOpening);
            }
        } else {
            if ($parenLevel !== 0) {
                return $this->fail("missing closing `)`", $lastParen);
            }
        }


        //chunk didn't fail


        //$this->emitChunk(true);
    }

    /**
     * @param $pos
     *
     * @return int
     */
    public function charCode($pos)
    {
        return ord($this->input[$pos]);
    }

    /**
     * @param      $msg
     * @param null $index
     */
    public function fail($msg, $index = null)
    {

        if (!$index) {
            $this->index = $this->parserCurrentIndex;
        } else {
            $this->index = $index;
        }
        $this->message = 'ParseError: ' . $msg;
    }


    /*
    function emitChunk( $force = false ){
        $len = $this->parserCurrentIndex - $this->emitFrom;
        if ((($len < 512) && !$force) || !$len) {
            return;
        }
        $chunks[] = substr($this->input, $this->emitFrom, $this->parserCurrentIndex + 1 - $this->emitFrom );
        $this->emitFrom = $this->parserCurrentIndex + 1;
    }
    */
}
