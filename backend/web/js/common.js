;(function() {
	//全局ajax处理
	$.ajaxSetup({
		complete: function(jqXHR) {
			//登录失效处理
		   if(jqXHR.responseText.state === 'logout') {
		   	location.href = GV.URL.LOGIN;
		   }
  	},
  	data : {
  		csrf_token : GV.TOKEN
  	},
		error : function(jqXHR, textStatus, errorThrown){
			//请求失败处理
			alert(errorThrown ? errorThrown : '操作失败');
		}
	});

	if($.browser.msie) {
		//ie 都不缓存
		$.ajaxSetup({
			cache : false
		});
	}

	//不支持placeholder浏览器下对placeholder进行处理
	if(document.createElement('input').placeholder !== '') {
		$('[placeholder]').focus(function() {
			var input = $(this);
			if(input.val() == input.attr('placeholder')) {
				input.val('');
				input.removeClass('placeholder');
			}
		}).blur(function() {
			var input = $(this);
			if(input.val() == '' || input.val() == input.attr('placeholder')) {
				input.addClass('placeholder');
				input.val(input.attr('placeholder'));
			}
		}).blur().parents('form').submit(function() {
			$(this).find('[placeholder]').each(function() {
				var input = $(this);
				if(input.val() == input.attr('placeholder')) {
					input.val('');
				}
			});
		});
	}


	//提交按钮是否固定底部
	setBtnWrap();
	/*$(window).on('resize', function(){
		setBtnWrap(true);
	});*/
	function setBtnWrap(reset){
		if(parent.Yr && parent.Yr.dialog) {
			//过滤弹窗
			return ;
		}

		if($('body').height() <= $(window).height()) {
			$('div.btn_wrap').removeClass('btn_wrap');
		}else{
			if(reset) {
				var par = $('button.J_ajax_submit_btn:last').parent().parent();
				if(!par.attr('class')) {
					//class一定为空
					par.addClass('btn_wrap');
				}
			}
		}
	}

	//iframe页面f5刷新
	$(document).on('keydown', function(event){
		var e = window.event || event;
		if(e.keyCode == 116) {
			e.keyCode = 0;

			var $doc = $(parent.window.document),
					id = $doc.find('#B_history .current').attr('data-id'),
					iframe = $doc.find('#iframe_'+ id);
			if(iframe[0].contentWindow) {
				//common.js
				reloadPage(iframe[0].contentWindow);
			}

			//!ie
			return false;
		}

	});

	//所有加了dialog类名的a链接，自动弹出它的href
	if( $('a.J_dialog').length ) {
		Yr.use('dialog',function() {
			$('.J_dialog').on( 'click',function(e) {
				e.preventDefault();
				var _this = $(this);
				Yr.dialog.open( $(this).prop('href') ,{
					onClose : function() {
						_this.focus();//关闭时让触发弹窗的元素获取焦点
					},
					title:_this.prop('title')
				});
			}).attr('role','button');

		});
	}

	//所有的ajax form提交,由于大多业务逻辑都是一样的，故统一处理
	var ajaxForm_list = $('form.J_ajaxForm');
	if( ajaxForm_list.length ) {
		Yr.use('dialog','ajaxForm',function() {

			if($.browser.msie) {
				//ie8及以下，表单中只有一个可见的input:text时，会整个页面会跳转提交
				ajaxForm_list.on('submit', function(e){
					//表单中只有一个可见的input:text时，enter提交无效
					e.preventDefault();
				});
			}


			$('button.J_ajax_submit_btn').on('click', function(e) {
				e.preventDefault();

				var btn = $(this),
					form = btn.parents('form.J_ajaxForm');

				//批量操作 判断选项
				if(btn.data('subcheck')) {
					btn.parent().find('span').remove();
					if(form.find('input.J_check:checked').length) {
						var msg = btn.data('msg');
						if(msg) {
							Yr.dialog({
								type : 'confirm',
								isMask	: false,
								message : btn.data('msg'),
								follow	: btn,
								onOk	: function() {
									btn.data('subcheck', false);
									btn.click();
								}
							});
						}else{
							btn.data('subcheck', false);
							btn.click();
						}

					}else{
						$( '<span class="tips_error">请至少选择一项</span>' ).appendTo(btn.parent()).fadeIn( 'fast' );
					}
					return false;
				}

				//ie处理placeholder提交问题
				if($.browser.msie) {
					form.find('[placeholder]').each(function() {
						var input = $(this);
						if(input.val() == input.attr('placeholder')) {
							input.val('');
						}
					});
				}

				form.ajaxSubmit({
					url : btn.data('action') ? btn.data('action') : form.attr('action'),			//按钮上是否自定义提交地址(多按钮情况)
					dataType	: 'json',
					beforeSubmit: function(arr, $form, options) {
						var text = btn.text();

						//按钮文案、状态修改
						btn.text(text +'中...').prop('disabled',true).addClass('disabled');
					},
					success		: function(data, statusText, xhr, $form) {
						var text = btn.text();

						//按钮文案、状态修改
						btn.removeClass('disabled').text(text.replace('中...', '')).parent().find('span').remove();

						if( data.state === 'success' ) {
							$( '<span class="tips_success">' + data.message + '</span>' ).appendTo(btn.parent()).fadeIn('slow').delay( 1000 ).fadeOut(function() {
								if(data.referer) {
									//返回带跳转地址
									if(window.parent.Yr.dialog) {
										//iframe弹出页
										window.parent.location.href = decodeURIComponent(data.referer);
									}else {
										window.location.href = decodeURIComponent(data.referer);
									}
								}else {
									if(window.parent.Yr.dialog) {
										reloadPage(window.parent);
									}else {
										reloadPage(window);
									}
								}
							});
						}else if( data.state === 'fail' ) {
							$( '<span class="tips_error">' + data.message + '</span>' ).appendTo(btn.parent()).fadeIn( 'fast' );
							btn.removeProp('disabled').removeClass('disabled');
						}
					}
				});
			});

		});
	}



    //dialog弹窗内的关闭方法
	$('#J_dialog_close').on('click', function(e){
		e.preventDefault();
		if(window.parent.Yr.dialog) {
			window.parent.Yr.dialog.closeAll();
		}
	});

	//所有的删除操作，删除数据后刷新页面
	if( $('a.J_ajax_del').length ) {
		Yr.use('dialog',function() {

			$('.J_ajax_del').on('click',function(e) {
				e.preventDefault();
				var $this = $(this), href = $this.prop('href'), msg = $this.data('msg'), pdata = $this.data('pdata');
				var params = {
					message	: msg ? msg : '确定要删除吗？',
					type	: 'confirm',
					isMask	: false,
					follow	: $(this),//跟随触发事件的元素显示
					onOk	: function() {
						$.ajax({
							url: href,
							type : 'post',
							dataType: 'json',
							data: function(){
								if(pdata) {
									pdata = $.parseJSON(pdata.replace(/'/g, '"'));
									return pdata
								}
							}(),
							success: function(data){
								if(data.state === 'success') {
									if(data.referer) {
										location.href = decodeURIComponent(data.referer);
									}else {
										reloadPage(window);
									}
								}else if( data.state === 'fail' ) {
									Yr.dialog.alert(data.message);
								}
							}
						});
					}
				};
				Yr.dialog(params);
			});

		});
	}

	//所有的请求刷新操作
	var ajax_refresh = $('a.J_ajax_refresh'),
		refresh_lock = false;
	if( ajax_refresh.length ) {
		ajax_refresh.on('click', function(e){
			e.preventDefault();
			if(refresh_lock) {
				return false;
			}
			refresh_lock = true;
			var pdata = $(this).data('pdata');

			$.ajax({
				url: this.href,
				type : 'post',
				dataType: 'json',
				data: function(){
					if(pdata) {
						pdata = $.parseJSON(pdata.replace(/'/g, '"'));
						return pdata
					}
				}(),
				success: function(data){
					refresh_lock = false;

					if(data.state === 'success') {
						if(data.referer) {
							location.href = decodeURIComponent(data.referer);
						}else {
							reloadPage(window);
						}
					}else if( data.state === 'fail' ) {
						Yr.dialog.alert(data.message);
					}
				}
			});

		});
	}

	//拾色器
	var color_pick = $('.J_color_pick');
	if(color_pick.length) {
		Yr.use('colorPicker',function() {
			color_pick.each(function() {
				$(this).colorPicker({
					default_color : 'url("'+ GV.URL.IMAGE_RES +'/transparent.png")',		//写死
					callback:function(color) {
						var em = $(this).find('em'),
							input = $(this).next('.J_hidden_color');

						em.css('background',  color);
						input.val(color.length === 7 ? color : '');
					}
				});
			});
		});
	}

	//字体配置
	if($('.J_font_config').length) {
		Yr.use('colorPicker',function() {
			var elem = $('.color_pick');
			elem.each(function() {
				var panel = $(this).parent('.J_font_config');
				var bg_elem = $(this).find('.J_bg');
				$(this).colorPicker({
					default_color : 'url("'+ GV.URL.IMAGE_RES +'/transparent.png")',
					callback:function(color) {
						bg_elem.css('background',  color);
						panel.find('.case').css('color',color.length === 7 ? color : '');
						panel.find('.J_hidden_color').val(color.length === 7 ? color : '');
					}
				});
			});
		});
		//加粗、斜体、下划线的处理
		$('.J_bold,.J_italic,.J_underline').on('click',function() {
			var panel = $(this).parents('.J_font_config');
			var c = $(this).data('class');
			if( $(this).prop('checked') ) {
				panel.find('.case').addClass(c);
			}else {
				panel.find('.case').removeClass(c);
			}
		});
	}

	/*复选框全选(支持多个，纵横双控全选)。
	 *实例：版块编辑-权限相关（双控），验证机制-验证策略（单控）
	 *说明：
	 *	"J_check"的"data-xid"对应其左侧"J_check_all"的"data-checklist"；
	 *	"J_check"的"data-yid"对应其上方"J_check_all"的"data-checklist"；
	 *	全选框的"data-direction"代表其控制的全选方向(x或y)；
	 *	"J_check_wrap"同一块全选操作区域的父标签class，多个调用考虑
	*/

	if($('.J_check_wrap').length) {
		var total_check_all = $('input.J_check_all');

		//遍历所有全选框
		$.each(total_check_all, function(){
			var check_all = $(this), check_items;

			//分组各纵横项
			var check_all_direction = check_all.data('direction');
				check_items = $('input.J_check[data-'+ check_all_direction +'id="'+ check_all.data('checklist') +'"]');

			//点击全选框
			check_all.change(function (e) {
				var check_wrap = check_all.parents('.J_check_wrap'); //当前操作区域所有复选框的父标签（重用考虑）

				if ($(this).attr('checked')) {
					//全选状态
					check_items.attr('checked', true);

					//所有项都被选中
					if( check_wrap.find('input.J_check').length === check_wrap.find('input.J_check:checked').length) {
						check_wrap.find(total_check_all).attr('checked', true);
					}

				} else {
					//非全选状态
					check_items.removeAttr('checked');

					//另一方向的全选框取消全选状态
					var direction_invert = check_all_direction === 'x' ? 'y' : 'x';
					check_wrap.find($('input.J_check_all[data-direction="'+ direction_invert +'"]')).removeAttr('checked');
				}

			});

			//点击非全选时判断是否全部勾选
			check_items.change(function(){

				if($(this).attr('checked')) {

					if(check_items.filter(':checked').length === check_items.length) {
						//已选择和未选择的复选框数相等
						check_all.attr('checked', true);
					}

				}else{
					check_all.removeAttr('checked');
				}

			});


		});

	}

	/*li列表添加&删除(支持多个)，实例(“验证机制-添加验证问题”，“附件相关-添加附件类型”)：
		<ul id="J_ul_list_verify" class="J_ul_list_public">
			<li><input type="text" value="111" ><a class="J_ul_list_remove" href="#">[删除]</a></li>
			<li><input type="text" value="111" ><a class="J_ul_list_remove" href="#">[删除]</a></li>
		</ul>
		<a data-related="verify" class="J_ul_list_add" href="#">添加验证</a>

		<ul id="J_ul_list_rule" class="J_ul_list_public">
			<li><input type="text" value="111" ><a class="J_ul_list_remove" href="#">[删除]</a></li>
			<li><input type="text" value="111" ><a class="J_ul_list_remove" href="#">[删除]</a></li>
		</ul>
		<a data-related="rule" class="J_ul_list_add" href="#">添加规则</a>
	*/
	var ul_list_add = $('a.J_ul_list_add');
	if(ul_list_add.length) {
		var new_key = 0;

		//添加
		ul_list_add.click(function(e){
			e.preventDefault();
			new_key++;
			var $this = $(this);

			//"new_"字符加上唯一的key值，_li_html 由列具体页面定义
			var $li_html = $(_li_html.replace(/new_/g, 'new_'+new_key));

			$('#J_ul_list_'+ $this.data('related')).append($li_html);
			$li_html.find('input.input').first().focus();
		});

		//删除
		$('ul.J_ul_list_public').on('click', 'a.J_ul_list_remove', function(e) {
			e.preventDefault();
			$(this).parents('li').remove();
		});
	}

	//日期选择器
	var dateInput = $("input.J_date")
	if(dateInput.length) {
		Yr.use('datePicker',function() {
			dateInput.datePicker();
		});
	}

	//日期+时间选择器
	var dateTimeInput = $("input.J_datetime");
	if(dateTimeInput.length) {
		Yr.use('datePicker',function() {
			dateTimeInput.datePicker({time:true});
		});
	}

	//图片上传预览
	if($("input.J_upload_preview").length) {
		Yr.use('uploadPreview',function() {
			$("input.J_upload_preview").uploadPreview();
		});
	}

	//代码复制
	var copy_btn = $('a.J_copy_clipboard'); //复制按钮
	if(copy_btn.length) {
		Yr.use('dialog', 'textCopy', function() {
			for(i=0, len=copy_btn.length; i<len; i++) {
				var item = $(copy_btn[i]);
				item.textCopy({
					content : $('#' + item.data('rel')).val()
				});
			}
		});
	}

	//tab
	var tabs_nav = $('ul.J_tabs_nav');
	if(tabs_nav.length) {
		Yr.use('tabs',function() {
			tabs_nav.tabs('.J_tabs_contents > div');
		});
	}

	//radio切换显示对应区块
	var radio_change = $('.J_radio_change');
	if(radio_change.length){

		var radio_c = radio_change.find('input:checked');
		if(radio_c.length) {
			radio_c.each(function(){
				var $this = $(this);
				//页面载入
				change($this.data('arr'), $this.parents('.J_radio_change'));
			});
		}

		//切换radio
		$('.J_radio_change input:radio').on('change', function(){
			change($(this).data('arr'), $(this).parents('.J_radio_change'));
		});

	}
	function change(str, radio_change) {
		var rel = $(radio_change.data('rel'));
		if(rel.length) {
			rel.hide();
		}else{
			$('.J_radio_tbody, .J_radio_change_items').hide();
		}

		if(str) {
			var arr= new Array();
			arr = str.split(",");


			$.each(arr, function(i, o){
				$('#'+ o).show();
			});
		}
	}

})();

//重新刷新页面，使用location.reload()有可能导致重新提交
function reloadPage(win) {
	var location = win.location;
	location.href = location.pathname + location.search;
}

function getCookie(name) {
		var nameEQ = name + "=";
		var ca = document.cookie.split(';');
		for(var i=0;i < ca.length;i++) {
			var c = ca[i];
			while (c.charAt(0)==' '){
				c = c.substring(1,c.length);
			}
			if (c.indexOf(nameEQ) == 0){
				return c.substring(nameEQ.length,c.length);
			}
		};

		return null;
	}

	function setCookie(name,value,days,domain) {
		if (days) {
			var date = new Date();
			date.setTime(date.getTime()+days*24*60*60*1000);
			var expires = '; expires='+date.toGMTString();
		}else{
			var expires = '';
		}
		document.cookie = name+"="+value+expires+"; domain="+domain+"; path=/";
	}

//浮出提示_居中
function resultTip(options) {

	var cls = (options.error ? 'warning' : 'success');
	var pop = $('<div style="left:50%;top:30%;" class="pop_showmsg_wrap"><span class="pop_showmsg"><span class="' + cls + '">' + options.msg + '</span></span></div>');

	pop.appendTo($('body')).fadeIn(function () {
		pop.css({
			marginLeft :  - pop.innerWidth() / 2
		}); //水平居中
	}).delay(1500).fadeOut(function () {
		pop.remove();

		//回调
		if (options.callback) {
			options.callback();
		}
	});

}

//弹窗居中定位 非ie6 fixed定位
function popPos(wrap){
	var ie6 = false,
			pos = 'fixed',
			top,
			win_height = $(window).height(),
			wrap_height = wrap.outerHeight();

	if($.browser.msie && $.browser.version < 7) {
		ie6 = true;
		pos = 'absolute';
	}

	if(win_height < wrap_height) {
		top = 0;
	}else{
		top = ($(window).height() - wrap.outerHeight())/2;
	}

	wrap.css({
		position : pos,
		top : top + (ie6 ? $(document).scrollTop() : 0),
		left : ($(window).width() - wrap.innerWidth())/2
	}).show();
}

//弹窗定位
Yr.Util.popPos = function(wrap){
	var ie6 = false,
			top,
			win_height = $(window).height(),
			wrap_height = wrap.outerHeight();

	if($.browser.msie && $.browser.version < 7) {
		ie6 = true;
	}

	if(win_height < wrap_height) {
		top = 0;
	}else{
		top = ($(window).height() - wrap.outerHeight())/2;
	}

	wrap.css({
		top : top + (ie6 ? $(document).scrollTop() : 0),
		left : ($(window).width() - wrap.innerWidth())/2,
		position : (ie6 ? 'absolute' : 'fixed')
	}).show();
}

(function(){
	//iframe内触发菜单 配合admin/index_run.htm里的方法 不支持跨域
	var tabframe_trigger = $('a.J_tabframe_trigger');
	if(tabframe_trigger.length) {
		try{
			var _SUBMENU_CONFIG = parent.window.SUBMENU_CONFIG;		//导航数据

			tabframe_trigger.on('click', function(e){
				e.preventDefault();
				var $this = $(this),
					id = $this.data('id'),						//id
					par = $this.data('parent'),					//父导航id
					level = parseInt($this.data('level'));		//二级三级导航标识

				parent.window.eachSubmenu(_SUBMENU_CONFIG, id, par, level, this.href);
			});
		}catch(e){
			$.error(e);
		}
	}
})();

(function(){
	//链接创建iframe，不支持触发菜单 不支持跨域
	var linkframe_trigger = $('a.J_linkframe_trigger');
	if(linkframe_trigger.length) {
		try{
			linkframe_trigger.on('click', function(e){
				e.preventDefault();
				var $this = $(this);
				parent.window.iframeJudge({
					elem: $this,
					href: $this.attr('href')
				});
			});
		}catch(e){
			$.error(e);
		}
	}
})();

//设置表单的值
function setValue(name, value) {
    var first = name.substr(0,1), input, i = 0, val;
    if(value === "") return;
    if("#" === first || "." === first) {
        input = $(name);
    } else {
        input = $("[name='" + name + "']");
    }

    if(input.eq(0).is(":radio")) { //单选按钮
        input.filter("[value='" + value + "']").each(function(){this.checked = true});
    } else if(input.eq(0).is(":checkbox")) { //复选框
        if(!$.isArray(value)){
            val = new Array();
            val[0] = value;
        } else {
            val = value;
        }
        for(i = 0, len = val.length; i < len; i++){
            input.filter("[value='" + val[i] + "']").each(function(){this.checked = true});
        }
    } else {  //其他表单选项直接设置值
        input.val(value);
    }
}