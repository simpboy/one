<?php
namespace Admin\Model;
use Think\Model;
class UpModel extends Model
{
    protected $tablePrefix = '';
    protected $tableName = 'ngc_mall';
    //保存图片
    public function save_img($savePath = '', $dir = '', $max_size = 5000000600) {
        vendor("UploadFile");
        $uploadpath = C('PICTURE_UPLOAD');
        $uploadpath = $uploadpath['rootPath'];
        $savePath = $savePath ? $savePath : $uploadpath. $dir;
        $upload = new \UploadFile();   // 实例化上传类
        $upload->maxSize = $max_size;  // 设置附件上传大小
        $upload->allowExts = ['jpg', 'gif', 'png', 'jpeg'];  // 设置附件上传类型
        $upload->savePath = $savePath;  // 设置附件上传目录
        $upload->saveRule = 'uniqid';
        // 上传错误提示错误信息
        if (!$upload->upload()) {
            debug_output($upload->getErrorMsg());
            return '';
        } // 上传成功 获取上传文件信息
        else {
            $info_array = $upload->getUploadFileInfo();
            $path = array();
            foreach($info_array as $info){
                if(!isset($path[$info['field']])){
                    $path[$info['field']] = $info['savename'] ? $uploadpath.$dir.$info['savename'] : '';
                }else{
                    $item_0 = $path[$info['field']];
                    unset($path[$info['field']]);
                    $path[$info['field']][] = $item_0;
                    $path[$info['field']][]   = $info['savename'] ? $uploadpath.$dir.$info['savename'] : '';
                }

            }
            return $path;
        }
    }

    //editorUpload 编辑器上传图片
    public function editor_upload($action){
        $uploadpath = C('PICTURE_UPLOAD');
        $uploadpath = $uploadpath['rootPath'];
        if (substr($uploadpath, 0, 2) == './') {
            $real_upload_path = SITE_ROOT.substr($uploadpath,1);
        }
        $config_string = '/* 前后端通信相关的配置,注释只允许使用多行方式 */
{
    /* 上传图片配置项 */
    "imageActionName": "uploadimage", /* 执行上传图片的action名称 */
    "imageFieldName": "upfile", /* 提交的图片表单名称 */
    "imageMaxSize": 2048000, /* 上传大小限制，单位B */
    "imageAllowFiles": [".png", ".jpg", ".jpeg", ".gif", ".bmp"], /* 上传图片格式显示 */
    "imageCompressEnable": true, /* 是否压缩图片,默认是true */
    "imageCompressBorder": 1600, /* 图片压缩最长边限制 */
    "imageInsertAlign": "none", /* 插入的图片浮动方式 */
    "imageUrlPrefix": "", /* 图片访问路径前缀 */
    "imagePathFormat": "'.$real_upload_path.'ueditor/php/upload/image/{time}{rand:6}", /* 上传保存路径,可以自定义保存路径和文件名格式 {yyyy}{mm}{dd}/ */
                                /* {filename} 会替换成原文件名,配置这项需要注意中文乱码问题 */
                                /* {rand:6} 会替换成随机数,后面的数字是随机数的位数 */
                                /* {time} 会替换成时间戳 */
                                /* {yyyy} 会替换成四位年份 */
                                /* {yy} 会替换成两位年份 */
                                /* {mm} 会替换成两位月份 */
                                /* {dd} 会替换成两位日期 */
                                /* {hh} 会替换成两位小时 */
                                /* {ii} 会替换成两位分钟 */
                                /* {ss} 会替换成两位秒 */
                                /* 非法字符 \ : * ? " < > | */
                                /* 具请体看线上文档: fex.baidu.com/ueditor/#use-format_upload_filename */

    /* 涂鸦图片上传配置项 */
    "scrawlActionName": "uploadscrawl", /* 执行上传涂鸦的action名称 */
    "scrawlFieldName": "upfile", /* 提交的图片表单名称 */
    "scrawlPathFormat": "/ueditor/php/upload/image/{yyyy}{mm}{dd}/{time}{rand:6}", /* 上传保存路径,可以自定义保存路径和文件名格式 */
    "scrawlMaxSize": 2048000, /* 上传大小限制，单位B */
    "scrawlUrlPrefix": "", /* 图片访问路径前缀 */
    "scrawlInsertAlign": "none",

    /* 截图工具上传 */
    "snapscreenActionName": "uploadimage", /* 执行上传截图的action名称 */
    "snapscreenPathFormat": "/ueditor/php/upload/image/{yyyy}{mm}{dd}/{time}{rand:6}", /* 上传保存路径,可以自定义保存路径和文件名格式 */
    "snapscreenUrlPrefix": "", /* 图片访问路径前缀 */
    "snapscreenInsertAlign": "none", /* 插入的图片浮动方式 */

    /* 抓取远程图片配置 */
    "catcherLocalDomain": ["127.0.0.1", "localhost", "img.baidu.com"],
    "catcherActionName": "catchimage", /* 执行抓取远程图片的action名称 */
    "catcherFieldName": "source", /* 提交的图片列表表单名称 */
    "catcherPathFormat": "/ueditor/php/upload/image/{yyyy}{mm}{dd}/{time}{rand:6}", /* 上传保存路径,可以自定义保存路径和文件名格式 */
    "catcherUrlPrefix": "", /* 图片访问路径前缀 */
    "catcherMaxSize": 2048000, /* 上传大小限制，单位B */
    "catcherAllowFiles": [".png", ".jpg", ".jpeg", ".gif", ".bmp"], /* 抓取图片格式显示 */

    /* 上传视频配置 */
    "videoActionName": "uploadvideo", /* 执行上传视频的action名称 */
    "videoFieldName": "upfile", /* 提交的视频表单名称 */
    "videoPathFormat": "/ueditor/php/upload/video/{yyyy}{mm}{dd}/{time}{rand:6}", /* 上传保存路径,可以自定义保存路径和文件名格式 */
    "videoUrlPrefix": "", /* 视频访问路径前缀 */
    "videoMaxSize": 102400000, /* 上传大小限制，单位B，默认100MB */
    "videoAllowFiles": [
        ".flv", ".swf", ".mkv", ".avi", ".rm", ".rmvb", ".mpeg", ".mpg",
        ".ogg", ".ogv", ".mov", ".wmv", ".mp4", ".webm", ".mp3", ".wav", ".mid"], /* 上传视频格式显示 */

    /* 上传文件配置 */
    "fileActionName": "uploadfile", /* controller里,执行上传视频的action名称 */
    "fileFieldName": "upfile", /* 提交的文件表单名称 */
    "filePathFormat": "/ueditor/php/upload/file/{yyyy}{mm}{dd}/{time}{rand:6}", /* 上传保存路径,可以自定义保存路径和文件名格式 */
    "fileUrlPrefix": "", /* 文件访问路径前缀 */
    "fileMaxSize": 51200000, /* 上传大小限制，单位B，默认50MB */
    "fileAllowFiles": [
        ".png", ".jpg", ".jpeg", ".gif", ".bmp",
        ".flv", ".swf", ".mkv", ".avi", ".rm", ".rmvb", ".mpeg", ".mpg",
        ".ogg", ".ogv", ".mov", ".wmv", ".mp4", ".webm", ".mp3", ".wav", ".mid",
        ".rar", ".zip", ".tar", ".gz", ".7z", ".bz2", ".cab", ".iso",
        ".doc", ".docx", ".xls", ".xlsx", ".ppt", ".pptx", ".pdf", ".txt", ".md", ".xml"
    ], /* 上传文件格式显示 */

    /* 列出指定目录下的图片 */
    "imageManagerActionName": "listimage", /* 执行图片管理的action名称 */
    "imageManagerListPath": "/ueditor/php/upload/image/", /* 指定要列出图片的目录 */
    "imageManagerListSize": 20, /* 每次列出文件数量 */
    "imageManagerUrlPrefix": "", /* 图片访问路径前缀 */
    "imageManagerInsertAlign": "none", /* 插入的图片浮动方式 */
    "imageManagerAllowFiles": [".png", ".jpg", ".jpeg", ".gif", ".bmp"], /* 列出的文件类型 */

    /* 列出指定目录下的文件 */
    "fileManagerActionName": "listfile", /* 执行文件管理的action名称 */
    "fileManagerListPath": "/ueditor/php/upload/file/", /* 指定要列出文件的目录 */
    "fileManagerUrlPrefix": "", /* 文件访问路径前缀 */
    "fileManagerListSize": 20, /* 每次列出文件数量 */
    "fileManagerAllowFiles": [
        ".png", ".jpg", ".jpeg", ".gif", ".bmp",
        ".flv", ".swf", ".mkv", ".avi", ".rm", ".rmvb", ".mpeg", ".mpg",
        ".ogg", ".ogv", ".mov", ".wmv", ".mp4", ".webm", ".mp3", ".wav", ".mid",
        ".rar", ".zip", ".tar", ".gz", ".7z", ".bz2", ".cab", ".iso",
        ".doc", ".docx", ".xls", ".xlsx", ".ppt", ".pptx", ".pdf", ".txt", ".md", ".xml"
    ] /* 列出的文件类型 */

}';
            $CONFIG = json_decode(preg_replace("/\/\*[\s\S]+?\*\//", "", $config_string), true);
            switch ($action) {
                case 'config':
                    $result = json_encode($CONFIG);
                    break;

                /* 上传图片 */
                case 'uploadimage':
                    /* 上传涂鸦 */
                case 'uploadscrawl':
                    /* 上传视频 */
                case 'uploadvideo':
                    /* 上传文件 */
                case 'uploadfile':
                    $result = $this->editor_upload_real($CONFIG);
                    break;
                default:
                    $result = json_encode(array(
                        'state'=> '请求地址出错'
                    ));
                    break;
            }


            /* 输出结果 */
            if (isset($_GET["callback"])) {
                if (preg_match("/^[\w_]+$/", $_GET["callback"])) {
                    echo htmlspecialchars($_GET["callback"]) . '(' . $result . ')';
                } else {
                    echo json_encode(array(
                        'state'=> 'callback参数不合法'
                    ));
                }
            } else {
                echo $result;
            }


        }

    protected function editor_upload_real($CONFIG){
            /* 上传配置 */
            $base64 = "upload";
            switch (htmlspecialchars($_GET['action'])) {
                case 'uploadimage':
                    $config = array(
                        "pathFormat" => $CONFIG['imagePathFormat'],
                        "maxSize"	=> $CONFIG['imageMaxSize'],
                        "allowFiles" => $CONFIG['imageAllowFiles']
                    );
                    $fieldName = $CONFIG['imageFieldName'];
                    break;
                case 'uploadscrawl':
                    $config = array(
                        "pathFormat" => $CONFIG['scrawlPathFormat'],
                        "maxSize" => $CONFIG['scrawlMaxSize'],
                        "allowFiles" => $CONFIG['scrawlAllowFiles'],
                        "oriName" => "scrawl.png"
                    );
                    $fieldName = $CONFIG['scrawlFieldName'];
                    $base64 = "base64";
                    break;
                case 'uploadvideo':
                    $config = array(
                        "pathFormat" => $CONFIG['videoPathFormat'],
                        "maxSize" => $CONFIG['videoMaxSize'],
                        "allowFiles" => $CONFIG['videoAllowFiles']
                    );
                    $fieldName = $CONFIG['videoFieldName'];
                    break;
                case 'uploadfile':
                default:
                    $config = array(
                        "pathFormat" => $CONFIG['filePathFormat'],
                        "maxSize" => $CONFIG['fileMaxSize'],
                        "allowFiles" => $CONFIG['fileAllowFiles']
                    );
                    $fieldName = $CONFIG['fileFieldName'];
                    break;
            }

            vendor('Uploader');
            /* 生成上传实例对象并完成上传 */
            $up = new \Uploader($fieldName, $config, $base64);
            /**
             * 得到上传文件所对应的各个参数,数组结构
             * array(
             *     "state" => "",          //上传状态，上传成功时必须返回"SUCCESS"
             *     "url" => "",            //返回的地址
             *     "title" => "",          //新文件名
             *     "original" => "",       //原始文件名
             *     "type" => ""            //文件类型
             *     "size" => "",           //文件大小
             * )
             */
            /* 返回数据 */
            $file_info  = $up->getFileInfo();
            $uploadurl  = C('DOMAIN');
            $file_info['url']   = $uploadurl.str_replace(SITE_ROOT,'',$file_info['url']);
            return json_encode($file_info);
    }
}

