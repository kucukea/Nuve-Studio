<?php
    ?>
<!DOCTYPE html>
<title>jsSyntaxTree</title>
<link rel="stylesheet" type="text/css" href="default.css" />
<script type="module" src="syntaxtree.js" async></script>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<meta name='viewport' content='width=device-width, initial-scale=1' />
<meta name="author" content="Andre Eisenbach" />
<meta name="description"
content="jsSyntaxtree - a syntax tree generator for linguists. Draw syntax trees from labelled bracket notation phrases and include them into your assignment/homework." />
<meta name="keywords" content="syntax tree, linguists, homework, labelled bracket notation" />

<h1>jsSyntaxTree</h1>

<div id="options">
    <select id="font">
        <option value="sans-serif" selected="selected">sans-serif</option>
        <option value="serif">serif</option>
        <option value="monospace">monospace</option>
        <option value="cursive">cursive</option>
        <option value="fantasy">fantasy</option>
    </select>&nbsp;
    <select id="fontsize">
        <option value="12">12</option>
        <option value="14">14</option>
        <option value="16" selected="selected">16</option>
        <option value="18">18</option>
        <option value="20">20</option>
        <option value="24">24</option>
        <option value="36">36</option>
    </select>&nbsp;
    <input type="checkbox" id="nodecolor" checked="checked" />
    <label for="nodecolor">Color</label>
    <input type="checkbox" id="autosub" checked="checked" />
    <label for="autosub">Auto subscript</label>
    <input type="checkbox" id="triangles" checked="checked" />
    <label for="triangles">Triangles</label>
    <input type="checkbox" id="bottom" />
    <label for="bottom">Align at bottom</label>
</div>
<?php
    $code_textarea='[S[SS[Özne[isimUnsuru[Özelİsim[ahmet]]]][SS[Yüklem[fiilUnsuru[VP[Fiil[oku]]][FiilKipi[du]]]]]]]';
    if($_GET["code"])
        $code_textarea = $_GET["code"];
    ?>
<div id="input">
    <h2>Phrase (labelled bracket notation)</h2>
    <?php echo '<textarea rows="5" id="code">'.$code_textarea.'</textarea>'; ?>
    <span id="parse-error"></span>
</div>

<div id="tree"><canvas id="canvas" width="100" height="100"></canvas></div>

<div id="tip"></div>

<footer>
    &copy; 2003-2020 IronCreek Software<br />
    Idea and linguistic guidance - <b>Mei Eisenbach</b><br />
    Coding &amp; design - <b>Andr&eacute; Eisenbach</b><br />
    <a href="https://github.com/int2str/jssyntaxtree">https://github.com/int2str/jssyntaxtree</a>
    <div id="version">&nbsp;</div>
</footer>
