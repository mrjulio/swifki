<style>
table td, table th {
    padding: 4px;
    border: 1px solid #ccc;
    text-align: center;
}
td:first-child {
    text-align: left;
}

.success {
    color: #009116;
}
.fail {
    color: #d00;
}
</style>
<table>
    <tr>
        <th>Parameter</th>
        <th>Minimum</th>
        <th>Current</th>
        <th>Status</th>
    </tr>
    <tr>
        <td>PHP Version</td>
        <td>5.3.1</td>
        <td><?php echo PHP_VERSION ?></td>
        <td><?php echo PHP_VERSION > '5.3.1' ? '<span class="success">Ok</span>' : '<span class="fail">Fail</span>' ?></td>
    </tr>
    <tr>
        <td>Cache dir</td>
        <td>writable</td>
        <td><?php echo is_writable(__DIR__ . '/../cache/') ? 'writable' : 'not writable' ?></td>
        <td><?php echo is_writable(__DIR__ . '/../cache/') ? '<span class="success">Ok</span>' : '<span class="fail">Fail</span>' ?></td>
    </tr>
    <tr>
        <td>Markdown</td>
        <td><a href="http://michelf.ca/projects/php-markdown/" target="_blank">http://michelf.ca/projects/php-markdown/</a></td>
        <td><?php echo file_exists(__DIR__ . '/../libs/markdown/markdown.php') ? 'found' : 'missing' ?></td>
        <td><?php echo file_exists(__DIR__ . '/../libs/markdown/markdown.php') ? '<span class="success">Ok</span>' : '<span class="fail">Fail</span>' ?></td>
    </tr>
    <tr>
        <td>GeShi</td>
        <td><a href="http://sourceforge.net/projects/geshi/files/geshi/" target="_blank">http://sourceforge.net/projects/geshi/files/geshi/</a></td>
        <td><?php echo file_exists(__DIR__ . '/../libs/geshi/geshi.php') ? 'found' : 'missing' ?></td>
        <td><?php echo file_exists(__DIR__ . '/../libs/geshi/geshi.php') ? '<span class="success">Ok</span>' : '<span class="fail">Fail</span>' ?></td>
    </tr>
    <tr>
        <td>jQuery jsTree</td>
        <td><a href="http://www.jstree.com/" target="_blank">http://www.jstree.com/</a></td>
        <td><?php echo file_exists(__DIR__ . '/assets/jstree/jquery.jstree.js') ? 'found' : 'missing' ?></td>
        <td><?php echo file_exists(__DIR__ . '/assets/jstree/jquery.jstree.js') ? '<span class="success">Ok</span>' : '<span class="fail">Fail</span>' ?></td>
    </tr>
</table>
