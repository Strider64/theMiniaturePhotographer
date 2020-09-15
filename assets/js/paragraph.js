function Add_paragraph_tags(input_text) {
    var lines = input_text.split("\n");
    line_is_inside_code_block = false;
    line_is_inside_pre_block = false;
    line_is_inside_table = false;
    lines.forEach(function (line, index) {
        if (line === '' || line.startsWith('<h') || line.startsWith('<p') || line.startsWith('<img') || line.startsWith('<pre') || line.startsWith('<code') || line.startsWith('<table') || line.startsWith('<li>') || line.startsWith('<ol>') || line.startsWith('</ol>') || line.startsWith('<ul>') || line.startsWith('</ul>') || line_is_inside_code_block || line_is_inside_pre_block || line_is_inside_table) {
        } else {
            lines[index] = '<p>' + line + '</p>';
            lines[index] = lines[index].replace('<p><blockquote>', '<blockquote><p>');
            lines[index] = lines[index].replace('</blockquote></p>', '</p></blockquote>');
        }
        if (line.endsWith('</code>')) {
            line_is_inside_code_block = false;
        } else if (line.endsWith('</pre>')) {
            line_is_inside_pre_block = false;
        } else if (line.endsWith('</table>')) {
            line_is_inside_table = false;
        } else if (line.startsWith('<code')) {
            line_is_inside_code_block = true;
        } else if (line.startsWith('<pre')) {
            line_is_inside_pre_block = true;
        } else if (line.startsWith('<table')) {
            line_is_inside_table = true;
        }
    });
    var new_text = lines.join('\n');
    return new_text;
}