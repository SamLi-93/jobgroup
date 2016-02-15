<?php

class Control_Pagination2 extends QUI_Control_Abstract
{
    function render()
    {
        $pagination = $this->pagination;
        $udi        = $this->get('udi', $this->_context->requestUDI());
        $length     = $this->get('length', 9);
        $slider     = $this->get('slider', 2);
        $prev_label = $this->get('prev_label', '上一页');
        $next_label = $this->get('prev_label', '下一页');
        $url_args   = $this->get('url_args');
		$js_func	= $this->get('js_func');
		//echo $url_args;
        //$context = QContext::instance();
        //$uri = preg_replace('/\/page\/\d+/', '', $context->requestURI());
        $qs = $_SERVER['QUERY_STRING'];
        if(empty($qs)){
            $qs = $this->search;
        }
        $out = "";

        /*if ($this->get('show_count'))
        {
            $out .= "<p>共 {$pagination['record_count']} 个条目</p>\n";
        }*/
        $limit = empty($_GET['limit']) ? null : $_GET['limit'];
        $out .= " ";
        $out .= " ";
        //$out .= '<ul id="' . h($this->id()) . "\">\n";
        
        $url_args = (array)$url_args;
        if ($pagination['current'] == $pagination['first'])
        {
            $out .= "<span class=\"disabled\"> <  上一页</span>\n";
        }
        else
        {
            $url_args['page'] = $pagination['prev'];
            if (!$js_func) $url = url($udi, $url_args)."?$qs";
			else $url = "javascript:$js_func({$pagination['prev']})";
            $out .= "<a href=\"{$url}\"> <  上一页</a>\n";
        }

        $base = $pagination['first'];
        $current = $pagination['current'];

        $mid = intval($length / 2);
        if ($current < $pagination['first'])
        {
            $current = $pagination['first'];
        }
        if ($current > $pagination['last'])
        {
            $current = $pagination['last'];
        }

        $begin = $current - $mid;
        if ($begin < $pagination['first'])
        {
            $begin = $pagination['first'];
        }
        $end = $begin + $length - 1;
        if ($end >= $pagination['last'])
        {
            $end = $pagination['last'];
            $begin = $end - $length + 1;
            if ($begin < $pagination['first'])
            {
                $begin = $pagination['first'];
            }
        }

        if ($begin > $pagination['first'])
        {
            for ($i = $pagination['first']; $i < $pagination['first'] + $slider && $i < $begin; $i ++)
            {
                $url_args['page'] = $i;
                $in = $i + 1 - $base;
                if (!$js_func) $url = url($udi, $url_args)."?$qs";
				else $url = "javascript:$js_func($in)";
                $out .= "<a href=\"{$url}\">{$in}</a>\n";
            }

            if ($i < $begin)
            {
                $out .= "...\n";
            }
        }

        for ($i = $begin; $i <= $end; $i ++)
        {
            $url_args['page'] = $i;
            $in = $i + 1 - $base;
            if ($i == $pagination['current'])
            {
                $out .= "<span class=\"current\">{$in}</span>\n";
            }
            else
            {
                if (!$js_func) $url = url($udi, $url_args)."?$qs";
				else $url = "javascript:$js_func($in)";
                $out .= "<a href=\"{$url}\">{$in}</a>\n";
            }
        }

        if ($pagination['last'] - $end > $slider)
        {
            $out .= "...\n";
            $end = $pagination['last'] - $slider;
        }

        for ($i = $end + 1; $i <= $pagination['last']; $i ++)
        {
            $url_args['page'] = $i;
            $in = $i + 1 - $base;
            if (!$js_func) $url = url($udi, $url_args)."?$qs";
			else $url = "javascript:$js_func($in)";
            $out .= "<a href=\"{$url}\">{$in}</a>\n";
        }

        if ($pagination['current'] == $pagination['last'])
        {
            $out .= "<span class=\"disabled\">下一页  ></span>\n";
        }
        else
        {
            $url_args['page'] = $pagination['next'];
            if (!$js_func) $url = url($udi, $url_args)."?$qs";
			else $url = "javascript:$js_func({$pagination['next']})";
            $out .= "<a href=\"{$url}\">下一页  ></a>\n";
        }

        $out .= "<div class=\"clear\"></div>\n";

        $url_args['page'] = 1;
        //$url = url($udi, $url_args);
        $qs1 = preg_replace('/&?limit=\d+/', '', $qs);
		$url = preg_replace('/\/page\/\d+/', '', QContext::instance()->requestURI());
        $out .= "<script>
        function show_num(param){
    		location=\"$url?$qs1\"+\"&limit=\"+param;
        }
        </script>";

        return $out;
    }
}
