    $(function() {
                //graceful degradation
                $('#ui_element').find('ul').css({
                    'left'	:	'-970px'
                }).siblings('.mh_right').css({
                    'left'	:	'200px'
                });
				
                var $arrow = $('#ui_element').find('.mh_right');
                var $menu  = $('#ui_element').find('ul');
                $arrow.bind('mouseenter',function(){
                    var $this 	= $(this);
                    $this.stop().animate({'left':'160px'},50);
                    $menu.stop().animate({'left':'202px'},500,function(){
                        $(this).find('a')
                        .unbind('mouseenter,mouseleave')
                        .bind('mouseenter',function(){
                            $(this).addClass('hover');
                        })
                        .bind('mouseleave',function(){
                            $(this).removeClass('hover');
                        });
                    });
                });
                $menu.bind('mouseleave',function(){
                    var $this 	= $(this);
                    $this.stop().animate({'left':'-970px'},500,function(){
                        $arrow.stop().animate({'left':'200px'},500);
                    });
                });
            });