<?php
if (isset($_POST['_wpnonce']) && wp_verify_nonce($_POST['_wpnonce'], 'save')) {
    if (isset($_POST['options'])) {
        $options = stripslashes_deep($_POST['options']);
        update_option('docxpresso', $options);
    } else {
        update_option('docxpresso', array());
    }
} else {
    $options = get_option('docxpresso', array());
}
?>
<div class="wrap">

    <h2>Docxpresso</h2>

    <p>
        For the complete documentation read <a href="http://www.docxpresso.com/plugins/wp-docxpresso" target="_blank">the official documentation</a> on how to use the short tag [docxpresso] in your posts or pages.
    </p>
    
    <h3>Quik Guide</h3>
    
    <ol>
        <li>Generate a document with the content that you want to insert in your Post or Page using your favourite Office Suite (MS Word, Libre Office or Open Office).</li>
        <li>Save your document in <strong>.odt</strong> or <strong>.ods</strong> format (availables in MS Word  and Excel since Word 2007 SP2).</li>
        <li>Create a new Post or Page or edit an existing one.</li>
        <li>Click on the <strong>Insert Document</strong> button located over the rich text editor.</li>
        <li>The media window will open and will let you upload the required file (.odt or .ods) or used a previously uploaded file.</li>
        <li>After choosing a file click on the <strong>Insert</strong> button.</li>
        <li>A [docxpresso] shortcode will be included with all the required data. </li>     
        <li>You may then add or not any additional content to your post but do not modify the contents of the Docxpresso shortcode.</li>
    </ol>
    
    <p>Whenever you publish the Post or Page the plugin will render the contents of your document :-)</p>
    
    <h3>Options</h3>

    <form action="" method="post">
        <?php wp_nonce_field('save') ?>
        <table class="form-table">
            <!--<tr>
                <th>Parse charts</th>
                <td>
                    <input type="checkbox" name="options[parseCharts]" value="1" <?php echo isset($options['parseCharts']) ? 'checked' : ''; ?>>
                    <p class="description">
                        if checked charts will be parsed with the help of the c3.js library included within the plugin.
                    </p>
                </td>
            </tr> --> 
            <tr>
                <th>Number format for table sorting</th>
                <td>
                    <select name="options[sortNumberFormat]">
                        <option value=".," <?php if (isset($options['sortNumberFormat']) && $options['sortNumberFormat'] == '.,') {echo 'selected';} ?> >., (dot: decimal, comma: thousands)</option>
                        <option value=",." <?php if (isset($options['sortNumberFormat']) && $options['sortNumberFormat'] == ',.') {echo 'selected';} ?> >,. (comma: decimal, point: thousands)</option>
                    </select>
                    <p class="description">
                        By the time being we onli allow for two standard number formats:.
                    </p>
                    <ul>
                        <li><strong>".,"</strong>: where "." stands for the decimal point separator and "," for the thousands separator.</li>
                        <li><strong>",."</strong>: where "," stands for the decimal point separator and "," for the thousands separator.</li>
                    </ul>
                </td>
            </tr>
            <tr>
                <th>External file sources</th>
                <td>
                    <p><input type="checkbox" name="options[remote]" value="1" <?php if (isset($options['remote']) && $options['remote'] == '1') {echo 'checked';} ?> />  Remote files</p>
                    <p class="description">
                        If selected will, by default, check for an external file. You may always edit manually the resulting shortcode to change this global option for a particular page or post.
                    </p>
                </td>
            </tr>
        </table>
        <p class="submit">
            <input class="button button-primary" type="submit" name="save" value="Save"/>
        </p>
    </form>

</div>
