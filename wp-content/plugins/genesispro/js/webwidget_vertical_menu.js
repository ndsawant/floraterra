(function(a){
    a.fn.webwidget_vertical_menu=function(p){
        var p=p||{};
        var l=p&&p.menu_width?p.menu_width:"200";
        var menu_position = p&&p.menu_position?p.menu_position:"right";
        var menu_style = p&&p.menu_style?p.menu_style:"normal";
        var w=a(this);
		l = l - 2;
        l += 'px';
        if(w.children("ul").length==0||w.find("li").length==0){
            dom.append("Require menu content");
            return null
        }
        init( menu_position, menu_style);
        function init(menu_position, menu_style){
            w.children("ul").find("a").css("display","block");
			if( menu_style == "normal" )
	            w.find("li:first").css("width",l);
    		else
			    w.find("li").css("width",l);
			w.find("li:has(ul)").addClass("wsigenesis-menu_dd");
			if( menu_style == "vertical" ){
				if(menu_position == 'left'){
					w.find("li:has(ul)").addClass("havesub_left");
					w.children("ul").children("li").find("ul").css("right",l).css("top","0px");
				}else{
					w.find("li:has(ul)").addClass("havesub_right");
					w.children("ul").children("li").find("ul").css("left",l).css("top","0px");
				}
			}else{
				w.find("li:has(ul)").addClass("havesub_"+menu_style);
			}
        }
		
		if( menu_style != "normal" ){
			w.find("li").hover(function(){
				if(jQuery(this).find('.sub-menu').find('.sub-menu').html() == null)
					jQuery(this).children("ul").stop();
				if( menu_style == "vertical" )
					jQuery(this).children("ul").toggle('fast');
				else if( menu_style == "accordion" ){
					jQuery(this).children("ul").slideToggle('fast');
				}
			});
		}
     }
})(jQuery);