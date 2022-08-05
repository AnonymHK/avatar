<?php
namespace Md;

class Avatar
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
     * 是否为圆形
     * @var bool
     */
    private $isCircular;

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
     * 文字颜色
     * @var int
     */
    private $txtColor;

    /**
     * 背景色
     * @var int
     */
    private $bgColor;


    /**
     * 颜色 [[浅][深]]
     * @var int[][][]
     */
    public $defaultColors = [
        [[229, 115, 115], [183, 28, 28]], [[211, 47, 47], [127, 0, 0]], [[244, 67, 54], [127, 0, 0]], [[229, 57, 53], [127, 0, 0]],
        [[240, 98, 146], [136, 14, 79]], [[194, 24, 91], [86, 0, 39]], [[197, 17, 98], [86, 0, 39]], [[188, 71, 123], [86, 0, 39]],
        [[186, 104, 200], [74, 20, 140]], [[123, 31, 162], [18, 0, 94]], [[156, 39, 176], [18, 0, 94]], [[124, 67, 189], [18, 0, 94]],
        [[149, 117, 205], [49, 27, 146]], [[81, 45, 168], [0, 0, 99]], [[103, 58, 183], [0, 0, 99]], [[103, 70, 195], [0, 0, 99]],
        [[121, 134, 203], [26, 35, 126]], [[63, 81, 181], [0, 0, 81]], [[48, 63, 159], [0, 0, 81]], [[83, 75, 174], [0, 0, 81]],
        [[100, 181, 246], [13, 71, 161]], [[33, 150, 243], [0, 33, 113]], [[25, 118, 210], [0, 33, 113]], [[84, 114, 211], [0, 33, 113]],
        [[79, 195, 247], [1, 87, 155]], [[3, 169, 244], [1, 87, 155]], [[2, 136, 209], [0, 47, 108]], [[79, 131, 204], [0, 47, 108]],
        [[77, 208, 225], [0, 96, 100]], [[0, 188, 212], [0, 96, 100]], [[0, 151, 167], [0, 54, 58]], [[66, 142, 146], [0, 54, 58]],
        [[77, 182, 172], [0, 77, 64]], [[0, 150, 136], [0, 37, 26]], [[0, 121, 107], [0, 37, 26]], [[57, 121, 107], [0, 37, 26]],
        [[129, 199, 132], [27, 94, 32]], [[76, 175, 80], [27, 94, 32]], [[56, 142, 60], [0, 51, 0]], [[76, 140, 74], [0, 51, 0]],
        [[174, 213, 129], [51, 105, 30]], [[139, 195, 74], [51, 105, 30]], [[104, 159, 56], [0, 61, 0]], [[98, 151, 73], [0, 61, 0]],
        [[220, 231, 117], [130, 119, 23]], [[205, 220, 57], [82, 76, 0]], [[175, 180, 43], [82, 76, 0]], [[180, 166, 71], [82, 76, 0]],
        [[255, 241, 118], [245, 127, 23]], [[255, 235, 59], [245, 127, 23]], [[251, 192, 45], [188, 81, 0]], [[255, 176, 76], [188, 81, 0]],
        [[255, 213, 79], [255, 111, 0]], [[255, 193, 7], [196, 62, 0]], [[255, 160, 0], [196, 62, 0]], [[255, 160, 64], [196, 62, 0]],
        [[255, 183, 77], [230, 81, 0]], [[255, 152, 0], [172, 25, 0]], [[245, 124, 0], [172, 25, 0]], [[255, 131, 58], [172, 25, 0]],
        [[255, 138, 101], [191, 54, 12]], [[255, 87, 34], [135, 0, 0]], [[249, 104, 58], [135, 0, 0]],
        [[161, 136, 127], [62, 39, 35]], [[121, 85, 72], [27, 0, 0]], [[106, 79, 75], [27, 0, 0]],
        [[144, 164, 174], [38, 50, 56]], [[96, 125, 139], [0, 10, 18]], [[79, 91, 98], [0, 10, 18]],
    ];

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
    public function __construct($nickName, $len = 2, $avatarSize = 200, $isCircular = true)
    {
        $nickLen = mb_strlen($nickName);
        if($len > $nickLen){
            $len = 1;
        }
        
        // 缩略字符个数
        $this->initialsLen = $len;

        // 缩略字符
        $this->initials = mb_strtoupper(mb_substr($nickName, 0, $this->initialsLen, "UTF-8"));

        // http://www.dafont.com/pacifico.font
        $this->fontPath = dirname(__FILE__) . '/../assets/fonts/Roboto-Medium.ttf';
        // 缩略字符如果超过1位且包含非字母数字，则随机取一个字符串
        if(!ctype_alnum($this->initials)){            
            if($this->initialsLen > 1){                
                //取出值，字符串截取方法 strlen获取字符串长度
                $this->initials = $this->randChar($this->initialsLen);                
            }
            else{
                //默认是纯数字或字母
                $this->isLetter = false;
                $this->fontPath = dirname(__FILE__) . '/../assets/fonts/NotoSansSC-Medium.otf';
            }
        }

        // 头像大小
        $this->avatarSize = $avatarSize;

        // 头像是否圆形
        $this->isCircular = $isCircular;

        // 随机颜色
        $this->randColor();

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
        
        if(!$this->isCircular){
            //拾取一个完全透明的颜色,最后一个参数127为全透明
            $bgAlpha = imagecolorallocatealpha($this->avatarImg, 255, 255, 255, 127);
            
            imagefill($this->avatarImg, 0, 0, $bgAlpha);
            $r = $w / 2; //圆半径
            //    $y_x = $r; //圆心X坐标
            //    $y_y = $r; //圆心Y坐标
            for ($x = 0; $x < $w; $x++) {
                for ($y = 0; $y < $h; $y++) {
                    //$rgbColor = imagecolorat($this->avatarImg, $x, $y);
                    if (((($x - $r) * ($x - $r) + ($y - $r) * ($y - $r)) < ($r * $r))) {
                        imagesetpixel($this->avatarImg, $x, $y, $bgAlpha);
                    }
                }
            }
        }
        else{
            //画一个居中圆形
            imagefilledellipse($this->avatarImg,
                $w / 2,
                $h / 2,
                $w,
                $h,
                $this->bgColor
            );
        }                

        //抗锯齿
        imageantialias($this->avatarImg, true);

        //字体颜色
        //$fontColor = imagecolorallocate($this->avatarImg, 255, 255, 255);

        $fontBox = imagettfbbox($this->fontSize, 0, $this->fontPath, $this->initials);//获取文字所需的尺寸大小 

        // 居中算法
        // ceil((700 - $fontBox[2]) / 2)  宽度
        // ceil(($height - $fontBox[1] - $fontBox[7]) / 2)  高度
        $fx = ceil(($this->avatarSize - $fontBox[2]) / 2);
        // 在圆正中央填入字符
        imagettftext($this->avatarImg, 
            $this->fontSize, 
            0, 
            $fx,
            $this->avatarSize, 
            $this->txtColor, $this->fontPath,  $this->initials
        );
        //全透明背景
        imagesavealpha($this->avatarImg, true);        
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
        $max = count($this->defaultColors) - 1;
        $i = mt_rand(0, $max);
        $color = $this->defaultColors[$i];

        $ii = mt_rand(0, 9) % 2;
        if ($ii === 0) {
            $this->txtColor = imagecolorallocate($this->avatarImg, $color[0][0], $color[0][1], $color[0][2]);
            $this->bgColor = imagecolorallocate($this->avatarImg, $color[1][0], $color[1][1], $color[1][2]);
        } else {
            $this->txtColor = imagecolorallocate($this->avatarImg, $color[1][0], $color[1][1], $color[1][2]);
            $this->bgColor = imagecolorallocate($this->avatarImg, $color[0][0], $color[0][1], $color[0][2]);
        }
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
     */
    public function save($path, $avatarSize = 0)
    {
        if (!$avatarSize) {
            $avatarSize = $this->avatarSize;
        }
        return imagepng($this->resize($avatarSize), $path);
    }
}