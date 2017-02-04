<?php

namespace common\tools;

/*
 * 自定义全局公共方法
 */
class tools{

    public $layout = false;

    /**
     *  短消息函数,可以在某个动作处理后友好的提示信息
     *
     * @param     string  $msg      消息提示信息
     * @param     string  $gourl    跳转地址
     * @param     int     $limittime  限制时间
     * @return    void
     */
    public function showMsg($msg, $gourl, $limittime=0)
    {
        if(empty($GLOBALS['cfg_plus_dir'])) $GLOBALS['cfg_plus_dir'] = '..';

        $htmlhead  = "<html>\r\n<head>\r\n<title提示信息</title>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\r\n";
        $htmlhead .= "<base target='_self'/>\r\n<style>div{line-height:160%;}</style></head>\r\n<body leftmargin='0' topmargin='0' bgcolor='#FFFFFF'>\r\n<center>\r\n<script>\r\n";
        $htmlfoot  = "</script>\r\n</center>\r\n</body>\r\n</html>\r\n";

        $litime = ($limittime==0 ? 1000 : $limittime);
        $func = '';

        if ($gourl=='-1') {
            if($limittime==0) $litime = 3000;
            $gourl = "javascript:history.go(-1);";
        }

        if ($gourl=='') {
            $msg = "<script>alert(\"".str_replace("\"","“",$msg)."\");</script>";
        } else {
            //当网址为:close::objname 时, 关闭父框架的id=objname元素
            if (preg_match('/close::/',$gourl)) {
                $tgobj = trim(preg_replace('/close::/', '', $gourl));
                $gourl = 'javascript:;';
                $func .= "window.parent.document.getElementById('{$tgobj}').style.display='none';\r\n";
            }

            $func .= "var pgo=0;
				function JumpUrl(){
					if(pgo==0){ location='$gourl'; pgo=1; }
				}\r\n";
            $rmsg = $func;
            $rmsg .= "document.write(\"<br /><div style='width:450px;padding:0px;border:1px solid #235179;'>";
            $rmsg .= "<div style='padding:5px 10px;font-size:12px;border-bottom:1px solid #235179;background:#235179;color:#FFFFFF;text-align:left;'><b>提示信息</b></div>\");\r\n";
            $rmsg .= "document.write(\"<div style='height:120px;font-size:10pt;background:#ffffff'><br />\");\r\n";
            $rmsg .= "document.write(\"".str_replace("\"","“",$msg)."\");\r\n";
            $rmsg .= "document.write(\"";

            if ( $gourl != 'javascript:;' && $gourl != '') {
                $rmsg .= "<br /><a href='{$gourl}'>如果你的浏览器没反应，请点击这里...</a>";
                $rmsg .= "<br/></div>\");\r\n";
                $rmsg .= "setTimeout('JumpUrl()',$litime);";
            } else {
                $rmsg .= "<br/></div>\");\r\n";
            }
            $msg  = $htmlhead.$rmsg.$htmlfoot;
        }
        echo $msg;
    }




}


?>