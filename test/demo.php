<title>Generate Avatars</title>
<h1>
Generate Avatars
</h1>
<p>
    <a href="https://github.com/anonymhk/avatar" target="_blank">Fork on Github: https://github.com/anonymhk/avatar</a>
</p>
<hr/>
<form action="?" method="post">
    <p>
    <h3>Input a char:</h3>
    <?php
    $testData = array('e','a','s','d','f',
			 'g','h','j','k','l','z','x','c','v','b','n','m','0','1',
			 '2','3','4','5','6','7','8','9','林','灿','斌','编','写',
			 '于','二','零','一','五','年','四','月','三','十','日');
    if (isset($_POST['char']) && $_POST['char'] != null) {
        $char = $_POST['char'];
    } else {
        $char = $testData[mt_rand(0, count($testData) - 1)];
    }
    ?>
    <input name="char" type="text" value="<?php echo $char; ?>"/>
    </p>
    <p>Size: <input type="range" name="size" min="16" max="512"
                    value="<?php echo (isset($_POST['size']) && intval($_POST['size'])) ? intval($_POST['size']) : '128'; ?>"/>
    </p>
    <p>
        &nbsp;&nbsp;<input type="submit" value="Generate"/>
    </p>
    <hr/>
    <p>
    <h3>Output:</h3>
    <img src="show.php?char=<?php echo urlencode($char); ?>&size=128&cache=<?php echo mt_rand(1, 965536); ?>">
    <img src="show.php?char=<?php echo urlencode($char); ?>&size=64&cache=<?php echo mt_rand(1, 965536); ?>">
    <img src="show.php?char=<?php echo urlencode($char); ?>&size=64&cache=<?php echo mt_rand(1, 965536); ?>">
    <img src="show.php?char=<?php echo urlencode($char); ?>&size=64&cache=<?php echo mt_rand(1, 965536); ?>">    
    <img src="show.php?char=<?php echo urlencode($char); ?>&size=32&cache=<?php echo mt_rand(1, 965536); ?>">
    </p>
    <?php
    if (isset($_POST['Size']) && intval($_POST['Size'])) {
        ?>
        <p>
            <img
                src="show.php?char=<?php echo urlencode($char); ?>&size=<?php echo intval($_POST['Size']); ?>&cache=<?php echo mt_rand(1, 65536); ?>">
        </p>
        <?php
    }
    ?>
</form>