<?php
namespace Md;

class Avatars
{
    /**
     * 头像资源句柄
     */
    private $avatarImg;

    // 头像尺寸(px)
    private $avatarSize;

    /**
     * 头像中字符个数
     * @var int
     */
    private $initialsLen;

    /**
     * 缩略字符
     * @var string
     */
    private $initials;

    /**
     * 是否为字母数字
     * @var bool
     */
    private $isLetter = true;

    private $fontSize = 16;

    private $fontPath;

    /**
     * 文字颜色[纯白色]
     * @var int
     */
    private $txtColor = 16514043;

    /**
     * 背景色
     * @var int
     */
    private $bgColor;


    /**
     * 颜色
     * @var int[][]
     */
    public $bgColors = array(
        array(239, 154, 154),
        array(229, 115, 115),
        array(244, 67, 54),
        array(229, 57, 53),
        array(211, 47, 47),
        array(198, 40, 40),
        array(183, 28, 28),
        array(255, 138, 128),
        array(255, 82, 82),
        array(255, 23, 68),
        array(213, 0, 0),
        array(248, 187, 208),
        array(244, 143, 177),
        array(236, 64, 122),
        array(233, 30, 99),
        array(216, 27, 96),
        array(194, 24, 91),
        array(173, 20, 87),
        array(136, 14, 79)
    );

    /**
     * 随机指定长度的字符串
     */
    private function randChar($len){
        $data = 'ABCDEFGHJKLMNPQRSTUVWXYZ12356789';
        $i = 0;
        $result = '';
        while($i < $len){
            $result .= $data[mt_rand(0,strlen($data) - 1)];
            $i++;
        }
        return $result;
    }

    /**
     * 构造方法
     * Avatar constructor.
     */
    public function __construct($nickName, $len = 2, $avatarSize = 200)
    {
        $nickLen = mb_strlen($nickName);
        if($len > $nickLen){
            $len = 1;
        }
        
        // 缩略字符个数
        $this->initialsLen = $len;

        // 头像大小
        $this->avatarSize = $avatarSize;

        // 缩略字符
        $this->initials = mb_strtoupper(mb_substr($nickName, 0, $this->initialsLen, "UTF-8"));

        // http://www.dafont.com/pacifico.font
        //Roboto-Medium.ttf | Heebo-Medium.ttf
        $this->fontPath = dirname(__FILE__) . '/../assets/fonts/Rubik-Medium.ttf';

        // 边距
        $padding = 40 * ($this->avatarSize / 256);
        $this->fontSize = ($this->avatarSize - $padding * 2.5) / $this->initialsLen;

        // 缩略字符如果超过1位且包含非字母数字，则随机取一个字符串
        if(!ctype_alnum($this->initials)){            
            if($this->initialsLen > 1){                
                //取出值，字符串截取方法 strlen获取字符串长度
                $this->initials = $this->randChar($this->initialsLen);                
            }
            else{
                //中文时 字体再小14
                $this->fontSize = $avatarSize > 60 ? $this->fontSize - 14 : $this->fontSize - 6;
                
                //默认是纯数字或字母
                $this->isLetter = false;
                //SourceHanSansCN-Normal.otf
                $this->fontPath = dirname(__FILE__) . '/../assets/fonts/NotoSansSC-Medium.otf';
            }
        }

        // 生成头像
        $this->makeAvatar();
    }

    /**
     * 生成头像
     */
    private function makeAvatar(){
        $w = $this->avatarSize;
        $h = $w;
        $this->avatarImg = imagecreatetruecolor($w, $h);

        //全透明背景
        imagesavealpha($this->avatarImg, true);

        // 随机颜色
        $this->randColor();
        //填充
        imagefill($this->avatarImg, 0, 0, $this->bgColor);
        //抗锯齿
        imageantialias($this->avatarImg, true);
        //获取文字所需的尺寸大小 
        $fontBox = imagettfbbox($this->fontSize, 0, $this->fontPath, $this->initials);

        // 居中算法
        // ceil((700 - $fontBox[2]) / 2)  宽度
        // ceil(($height - $fontBox[1] - $fontBox[7]) / 2)  高度
        $fx = ceil(($this->avatarSize - $fontBox[2]) / 2);
        $fy = ceil(($this->avatarSize - $fontBox[1] - $fontBox[7]) / 2);
        // 在圆正中央填入字符
        imagettftext($this->avatarImg, 
            $this->fontSize, 
            0,
            $fx,
            $fy, 
            $this->txtColor, $this->fontPath,  $this->initials
        );
    }
    
    /**
     * 销毁头像
     *
     * @return bool
     */
    public function freeAvatar()
    {
        return imagedestroy($this->avatarImg);
    }

    /**
     * 随机颜色
     */
    private function randColor(): void
    {
        $max = count($this->bgColors) - 1;
        $i = mt_rand(0, $max);
        $color = $this->bgColors[$i];
        $this->bgColor = imagecolorallocate($this->avatarImg, $color[0], $color[1], $color[2]);
    }

    private function resize($targetSize)
    {
        if (!isset($this->avatarImg)) {
            return false;
        }
        if ($this->avatarSize > $targetSize) {
            $percent         = $targetSize / $this->avatarSize;
            $targetWidth     = round($this->avatarSize * $percent);
            $targetHeight    = round($this->avatarSize * $percent);
            $targetImageData = imagecreatetruecolor($targetWidth, $targetHeight);
            //全透明背景
            imagesaveAlpha($targetImageData, true);
            $bgAlpha = imagecolorallocatealpha($targetImageData, 255, 255, 255, 127);
            //抗锯齿
            imageantialias($targetImageData, true);

            $r = $targetSize / 2; //圆半径
            //    $y_x = $r; //圆心X坐标
            //    $y_y = $r; //圆心Y坐标
            for ($x = 0; $x < $targetWidth; $x++) {
                for ($y = 0; $y < $targetHeight; $y++) {
                    //$rgbColor = imagecolorat($this->avatarImg, $x, $y);
                    if (((($x - $r) * ($x - $r) + ($y - $r) * ($y - $r)) < ($r * $r))) {
                        imagesetpixel($targetImageData, $x, $y, $bgAlpha);
                    }
                }
            }                

            imagefill($targetImageData, 0, 0, $bgAlpha);
            imagecopyresampled($targetImageData, $this->avatarImg, 0, 0, 0, 0, $targetWidth, $targetHeight, $this->avatarSize, $this->avatarSize);
            return $targetImageData;
        } else {
            return $this->avatarImg;
        }
    }

    /**
     * png格式显示头像
     *
     */
    public function outputBrowser()
    {
        header('Content-Type: image/png');
        imagepng($this->avatarImg);
    }

    /**
     * 输出Base64格式图像
     *
     */
    public function outputBase64()
    {
        $printContent = 'data:image/png;base64,' .base64_encode($this->avatarImg);
        header('Content-Type: image/png');
        echo $printContent;
    }

    /**
     * 保存头像
     * @param string $path  要保存的文件夹
     * @param string $fileName  文件名
     * @param int $avatarSize  头像大小
     * @return bool
     */
    public function save($path, $fileName, $avatarSize = 0)
    {
        if (!$avatarSize) {
            $avatarSize = $this->avatarSize;
        }
        if(!is_dir($path)){
            $makeStatus = mkdir($path, 0777, true);
            if(!$makeStatus){
                return false;
            }
        }
        return imagepng($this->resize($avatarSize), $path.$fileName);
    }
}