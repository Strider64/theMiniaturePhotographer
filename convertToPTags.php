<?php

function nl2p($text) {
// local variables
    $returntext = '';       // modified string to return back to caller
    $sections = array();  // array of text sections returned by preg_split()
    $pattern1 = '%        # match: <tag attrib="xyz">contents</tag>
 ^                       # tag must start on the beginning of a line
 (                       # capture whole thing in group 1
   <                     # opening tag starts with left angle bracket
   (\w++)                # capture tag name into group 2
   [^>]*+                # allow any attributes in opening tag
   >                     # opening tag ends with right angle bracket
   .*?                   # lazily grab everything up to closing tag
   </\2>                 # closing tag for one we just opened
 )                       # end capture group 1
 $                       # tag must end on the end of a line
 %smx';                  // s-dot matches newline, m-multiline, x-free-spacing

    $pattern2 = '%        # match: \n--untagged paragraph--\n
 (?:                     # non-capture group for first alternation. Match either...
   \s*\n\s*+             # a newline and all surrounding whitespace (and discard)
 |                       # or...
   ^                     # the beginning of the string
 )                       # end of first alternation group
 (.+?)                   # capture all text between newlines (or string ends)
 (?:\s+$)?               # clear out any whitespace at end of string
 (?=                     # end of paragraph is position followed by either...
   \s*\n\s*              # a newline with optional surrounding whitespace
 |                       # or...
   $                     # the end of the string
 )                       # end of second alternation group
 %x';                    // x-free-spacing
// first split text into tagged portions and untagged portions
// Note that the array returned by preg_split with PREG_SPLIT_DELIM_CAPTURE flag will get one
// extra member for each set of capturing parentheses. In this case, we have two sets; 1 - to
// capture the whole HTML tagged section, and 2 - to capture the tag name (which is needed to
// match the closing tag).
    $sections = preg_split($pattern1, $text, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);

// now put it back together proccessing only the untagged sections
    for ($i = 0; $i < count($sections); $i++) {
        if (preg_match($pattern1, $sections[$i])) { // this is a tagged paragraph, don't modify it, just add it (and increment array ptr)
            $returntext .= "\n" . $sections[$i] . "\n";
            $i++; // need to skip over the extra array element for capture group 2
        } else { // this is an untagged section. Add paragraph tags around bare paragraphs
            $returntext .= preg_replace($pattern2, "\n<p>$1</p>\n", $sections[$i]);
        }
    }
    $returntext = preg_replace('/^\s+/', '', $returntext); // clean leading whitespace
    $returntext = preg_replace('/\s+$/', '', $returntext); // clean trailing whitespace
    return $returntext;
}
