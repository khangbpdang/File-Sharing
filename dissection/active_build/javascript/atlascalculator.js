/* AtlasCalculator.js created by: www.uxmega.com */
 
var Calculator = {
	official_max_level: 101
	, bin: function(){
		$(document).on('click','[skill-id]',function(){
			var $o = $(this).closest('[skill-tree]'),
				$t = $o.attr('skill-tree'),
				$p = $("nav.skills li[skill-tree='"+$t+"']"),
				$v = $p.attr('skill-tree-unlock');
			
			if (!Calculator.loading && $p.attr('skill-tree-locked') == 'true')
					return Calculator.alert('Unlock '+$t+' in the '+$v+' tree.');		

			if ($(this).attr('skill-selected') == 'true') return Calculator.skill_unselect($(this));
			if (Calculator.unlock_only) return;
			Calculator.skill_select($(this));
		}).on('click','nav.skills [skill-tree]',function(){
			Calculator.tree_select($(this));
			if ($(this).attr('skill-tree-locked') === 'true')
				Calculator.lock_alert('Unlock '+$(this).attr('skill-tree')+' in the '+$(this).attr('skill-tree-unlock')+' tree.');
			else 
				Calculator.no_lock_alert();
		}).on('click','.total-points',function(){
			$(".point-options").attr('active',true);
		}).on('click','.options .tabs [tab]',function(e){
			e.preventDefault();
			var $nav = $(this).parent();
			var $par = $nav.parent();
			$par.find('[tab][active]').removeAttr('active');
			$par.find('[tab="'+$(this).attr('tab')+'"]').attr('active',true);
		}).on('submit','.options form',function(){
			$(".options[active]").removeAttr('active');
		}).on('click','.options > article,.options .close',function(){
			$(".options[active]").removeAttr('active');
		}).on('click','.alert',function(){
			$(".alert").hide();
		}).on('click','.reset',function(){
			if (confirm('Are you sure you want to reset all skill trees?')) {
				if (localStorage) {
					if (localStorage['skills']) localStorage.removeItem('skills');
					if (localStorage['max-points']) localStorage.removeItem('max-points');
					if (localStorage['my-level']) localStorage.removeItem('my-level');
					if (localStorage['last-skill-tree']) localStorage.removeItem('last-skill-tree');
				}
				return window.location = window.location.pathname;
			}
		}).on('click','.info',function(){
			$(".info").hide();
		});
		Calculator.bin = null;
	}
	, lock_alert: function($text) {
		$("[skill-tree][active] .header p").show().html('<i class="fa fa-fw fa-lock"></i>'+$text);
	}
	, no_lock_alert: function() {
		$("[skill-tree][active] .header p").hide().html('');
	}
	, alert:function($text){
		$(".alert > div").html($text)
		$(".alert").show();
		if (Calculator.alert_timeout) window.clearTimeout(Calculator.alert_timeout);
		Calculator.alert_timeout = setTimeout(function(){
			$(".alert").fadeOut();
		},7500);
	}
	, current_points: function(){
		var $count = 0;
		$("nav.skills li[skill-tree]").each(function(){
			$count += parseInt($(this).find('span').text());
		});
		$(".total-points .points-left").addClass('flash').html(Calculator.max_points - $count);
		setTimeout(function(){
			$(".flash").removeClass('flash');
		},250);
	}
	, set_max_points: function($level,$points){
		if ($level)	{
			$(".total-points > span > span").html('Level '+$level+' (official)');
			$level;
			$points = 0;
			var $i = 1;		
			while ($i <= $level) {
				switch (true) {
					case $i == 1:
						$points += 0;
						break;
					case 2 <= $i && $i <= 8:
						$points += 3;
						break;
					case 9 <= $i && $i <= 17:
						$points += 4;
						break;
					case 18 <= $i && $i <= 21:
						$points += 5;
						break;
					case 22 <= $i && $i <= 37:
						$points += 6;
						break;
					case 38 <= $i && $i <= 48:
						$points += 7;
						break;
					case 49 <= $i && $i <= 58:
						$points += 8;
						break;
					case 59 <= $i && $i <= 68:
						$points += 9;
						break;
					case 69 <= $i && $i <= 75:
						$points += 10;
						break;
					case 76 <= $i && $i <= 80:
						$points += 11;
						break;
					case 81 <= $i && $i <= 88:
						$points += 12;
						break;
					case 89 <= $i && $i <= 99:
						$points += 13;
						break;
					case 100 <= $i && $i <= 101:
						$points += 14;
						break;
					default:
						$points += 0;
				}
				$i++;
			}
			if (localStorage) {
				if (localStorage['max-points']) localStorage.removeItem('max-points');
				localStorage['my-level'] = $level;
			}
		} else {
			if (localStorage) {
				if (localStorage['my-level']) localStorage.removeItem('my-level');
				localStorage['max-points'] = $points;
			}
			$(".total-points > span > span").html('Points Available');
		}
		Calculator.max_points = $points;
		Calculator.current_points();
		
		return $points;
	}
	, tree_select: function($launcher) {
		$("[skill-tree][active]").removeAttr('active');
		$("[skill-tree='"+$launcher.attr('skill-tree')+"']").attr('active',true);
		if (localStorage) localStorage['last-skill-tree'] = $launcher.attr('skill-tree');
		$("ul[skill-tree][active]").eq(0).scrollLeft($("ul[skill-tree][active] [skill-id]:eq(0)").position().left - $("ul[skill-tree][active] [skill-id]:eq(0)").width()/2);
	}
	, skill_select: function ($launcher) {
		if ($launcher.attr('skill-selected')) return;
		if (Calculator.unlock_only) return;
		var $points = parseInt($launcher.attr('skill-points'));
		var $counter = $("nav.skills li[skill-tree='"+$launcher.closest('[skill-tree]').attr('skill-tree')+"'] span");
		if (parseInt($(".points-left").text()) - $points < 0) return Calculator.alert('Not enough skill points to select this skill.');

		$launcher.attr('skill-selected',true);
		$("#tooltip[skill-id='"+$launcher.attr('skill-id')+"']").attr('selected',true);
		
		var $requires2 = $launcher.attr('skill-requires-or');
		if ($requires2) {
			var $found = false;
			$.each($requires2.split(','),function($i,$v){
				if ($("[skill-id='"+$v+"']").attr('skill-selected') == 'true') $found = true;
			});
			if (!$found) $("ul[active] [skill-id='"+$requires2.split(',')[0]+"']").trigger('click');
		}
		
		var $requires = $launcher.attr('skill-requires-and');
		if ($requires) {
			$.each($requires.split(','),function($i,$v){
				$("ul[active] [skill-id='"+$v+"']:not([skill-selected])").trigger('click');
			});
		}
		$counter.html(parseInt($counter.html()) + $points);
		$counter.closest('li').addClass('not-zero');
		Calculator.current_points();
		if ($launcher.attr('skill-tree-unlock')) {
			$("nav.skills li[skill-tree='"+$launcher.attr('skill-tree-unlock')+"'][skill-tree-locked='true']").attr('skill-tree-locked','false').addClass('flash');
			setTimeout(function(){
				$(".flash").removeClass('flash');
			},250);
		}
		if (localStorage) {
			var $key = $launcher.closest('[skill-tree]').attr('skill-tree')+':'+$launcher.attr('skill-id')
			var $s;
			if (localStorage['skills']) $s = JSON.parse(localStorage['skills']);
			else $s = [];
			if ($s.indexOf($key) == -1) $s.push($key);
			localStorage['skills'] = JSON.stringify($s);
		}
		if ($(window).width() < 1000 && !Calculator.loading) Calculator.info_show($launcher);
	}
	, make_html: function($launcher) {
		var $html = '';
		$html += '<b>'+$launcher.attr('skill-title')+'</b>';
		$html += '<p>'+$launcher.attr('skill-description')+'</p>';
		$html += '<em>Unlock for '+$launcher.attr('skill-points')+ ' point'+($launcher.attr('skill-points')==1?'':'s')+'</em>';
		$html += '<small>Also Requires: ';
		if ($launcher.attr('skill-requires-and')) {
			var $p = $launcher.attr('skill-requires-and').split(','),$x;
			$.each($p,function(i,v){
				if (i > 0) $html += ' and ';
				$x = $("[skill-id='"+v+"']").attr('skill-title');
				$html += $x;
			});
		} else if ($launcher.attr('skill-requires-or')) {
			var $p = $launcher.attr('skill-requires-or').split(','),$x;
			$.each($p,function(i,v){
				if (i > 0) $html += ' or ';
				$x = $("[skill-id='"+v+"']").attr('skill-title');
				$html += $x;
			});
		}
		$html += '</small>';
		return $html;
	}
	, info_show: function($launcher) {
		$html = Calculator.make_html($launcher);
		$(".info > div").html($html);
		$(".info").show();		
	}
	, skill_unselect: function($launcher) {
		if (!$launcher.attr('skill-selected')) return;
		$launcher.removeAttr('skill-selected');
		$("#tooltip[skill-id='"+$launcher.attr('skill-id')+"']").removeAttr('selected');
		var $points = parseInt($launcher.attr('skill-points'));
		var $counter = $("nav.skills li[skill-tree='"+$launcher.closest('[skill-tree]').attr('skill-tree')+"'] span");
		$counter.html(parseInt($counter.html()) - $points);
		if (parseInt($counter.html()) == 0) $counter.closest('li').removeClass('not-zero');	
		Calculator.current_points();
		Calculator.unlock_only = true;
		$("[skill-requires-and*='"+$launcher.attr('skill-id')+"'][skill-selected]").each(function(){Calculator.skill_unselect($(this));});
		$("[skill-requires-or*='"+$launcher.attr('skill-id')+"'][skill-selected]").each(function(){Calculator.skill_unselect($(this));});
		Calculator.unlock_only = false;
		if ($launcher.attr('skill-tree-unlock')) $("nav.skills li[skill-tree='"+$launcher.attr('skill-tree-unlock')+"'][skill-tree-locked='false']").attr('skill-tree-locked','true');
		if (localStorage) {
			var $key = $launcher.closest('[skill-tree]').attr('skill-tree')+':'+$launcher.attr('skill-id')
			if (localStorage['skills'] && localStorage['skills']) {
				var $d = JSON.parse(localStorage['skills']);
				var $i = $d.indexOf($key);
				if ($i != -1) {
					delete $d[$i];
					localStorage['skills'] = JSON.stringify($d);
				}
			}
		}
		$(".info:visible").hide();
	}
	, ini: function(){
		Calculator.loading = true;
		if (typeof Calculator.bin == 'function') Calculator.bin();
		if (!localStorage) {
			Calculator.set_max_points(Calculator.official_max_level);
			$("ul[skill-tree]").removeAttr('active');
			$("nav li[skill-tree]:eq(0)").trigger('click');
		}
		if (localStorage) {
			if (!localStorage['max-points'] && !localStorage['my-level']) 
				Calculator.set_max_points(Calculator.official_max_level);

			if (localStorage['max-points']) {
				Calculator.set_max_points(0,localStorage['max-points']);		
				$(".options.point-options [tab='unofficial']").trigger('click');
				$(".options.point-options [tab='unofficial'] input").val(localStorage['max-points']);
			}
			if (localStorage['my-level']) {
				Calculator.set_max_points(localStorage['my-level']);		
				$(".options.point-options [tab='official']").trigger('click');
				$(".options.point-options [tab='official'] input").val(localStorage['my-level']);
			}
			if (localStorage['skills']) {
				var $k = JSON.parse(localStorage['skills']);
				$.each($k,function(i,v){
					if (!v) return;
					var $p = v.split(':');
					$("ul[skill-tree='"+$p[0]+"'] [skill-id='"+$p[1]+"']").trigger('click');
				});
			}
			if (localStorage['last-skill-tree'] && $("li[skill-tree='"+localStorage['last-skill-tree']+"']").length) {
				$("li[skill-tree='"+localStorage['last-skill-tree']+"']").trigger('click');
			} else {
				$("ul[skill-tree]").removeAttr('active');
				$("nav li[skill-tree]:eq(0)").trigger('click');
			}
		}
		Calculator.loading = false;
	}
};
$(document).ready(Calculator.ini);

 
var ToolTip = {
	bin: function(){
		$(document).on('mouseenter','[skill-id]',function(){
			if ($(window).width() < 1000) return;
			ToolTip.show($(this));
		}).on('mouseleave','[skill-id]',function(){
			if ($(window).width() < 1000) return;
			ToolTip.hide();
		});
		ToolTip.bin = null;
	}
	, ini: function(){
		if (typeof ToolTip.bin == 'function') ToolTip.bin();
	}
	, show: function($launcher){
		if ($launcher.attr('skill-selected')) $("#tooltip").attr('selected',true);
		else $("#tooltip[selected]").removeAttr('selected');
		if (!$launcher.offset()) return;
		if (!$launcher.attr('skill-title')) return $("#tooltip").hide();
		$("#tooltip").attr('skill-id',$launcher.attr('skill-id'));
		$("#tooltip > div").html(Calculator.make_html($launcher));
		$("#tooltip").css({
			left: $launcher.offset().left + $launcher.outerWidth(),
			top: $launcher.offset().top + ($launcher.outerHeight()/2) - ($("#tooltip").outerHeight()/2) - 16
		}).show();
	}
	, hide: function(){
		$("#tooltip").hide();
	}
};
$(document).ready(ToolTip.ini);

 
 
var TextTip = {
	bin: function(){
		$(document).on('mouseenter','.text-tip [alt]',function(){
			if ($(window).width() < 1000) return;
			if (TextTip.hider) clearTimeout(TextTip.hider);
			TextTip.show($(this));
		}).on('mouseleave','.text-tip [alt]',function(){
			if ($(window).width() < 1000) return;
			TextTip.hider = setTimeout(function(){TextTip.hide();},100);
		});
		TextTip.bin = null;
	}
	, ini: function(){
		if (typeof TextTip.bin == 'function') TextTip.bin();
		if ($(window).width() < 1000) return;
		if (!localStorage) return;
		if (localStorage['level-helper']) return;
		localStorage['level-helper'] = true;
		var $l = $("nav.skills li:first-of-type");
		$("#texttip").html('Click here to set your level');
		$("#texttip").css({
			left: ($l.offset().left + ($l.outerWidth()/2)) - ($("#texttip").outerWidth()/2),
			top: $l.offset().top + $l.outerHeight()
		}).show();
		TextTip.timeout = setTimeout(function(){
			$("#texttip").fadeOut();
		},5500);
	}
	, show: function($launcher){
		if (TextTip.timeout) clearTimeout(TextTip.timeout);
		$("#texttip").html($launcher.attr('alt'));
		$("#texttip").css({
			left: ($launcher.parent().offset().left + ($launcher.parent().outerWidth()/2)) - ($("#texttip").outerWidth()/2),
			top: $launcher.parent().offset().top + $launcher.parent().outerHeight()
		}).show();
	}
	, hide: function(){
		$("#texttip").hide();
	}
};
$(document).ready(TextTip.ini);
 
var Load = {
	bin: function(){
		$(document).on('click','#load .close',function(){
			Load.hide();
		});
		Load.bin = null;
	}
	, go: function(){
		if (!window.location.search || window.location.search.indexOf('ac_') == -1) return;
		var $qs = window.location.search.substr(1), $k;
		$qs = $qs.split('&');		
		var $id;
		$.each($qs,function(i,v){
			var $y = v.split('=')[0];
			if ($y.indexOf('ac_') == 0)
				$id = $y.substr(3);
		});
		if (!$id) return;
		$("#load").show();
		$("#load .id").html($id);
		window.history.pushState(null, null, '/');
		API.hit('load',{
			id: $id
		},function($d){
			if ($d.err) {
				$("#load").hide();
				return Calculator.alert($d.err);
			}
			if ($d.data) {
				Load.reset();
				Calculator.loading = true;
				$.each($d.data,function(i,v){
					if (!v) return;
					var $p = v.split(':');
					$("ul[skill-tree='"+$p[0]+"'] [skill-id='"+$p[1]+"']").trigger('click');
				});
				Calculator.loading = false;
				$("#load").hide();
			}
		});
	}
	, reset: function(){
		if (localStorage) {
			if (localStorage['skills']) localStorage.removeItem('skills');
			if (localStorage['max-points']) localStorage.removeItem('max-points');
			if (localStorage['my-level']) localStorage.removeItem('my-level');
			if (localStorage['last-skill-tree']) localStorage.removeItem('last-skill-tree');
		}
		$.each($("[skill-id][skill-selected]"),function(){Calculator.skill_unselect($(this));});
	}
	, hide: function(){
		$("#load").hide();
	}
	, ini: function(){
		if (typeof Load.bin == 'function') Load.bin();
		Load.go();
	}
};
$(document).ready(Load.ini);

  
var Share = {
	bin: function(){
		$(document).on('click','.share',function(){
			Share.go();
		}).on('click','#share .close',function(){
			Share.hide();
		});
		Share.bin = null;
	}
	, go: function(){
		var $skills = [];
		$.each($("[skill-id][skill-selected]"),function(){
			var $k = $(this).closest('[skill-tree]').attr('skill-tree') + ':' + $(this).attr('skill-id');
			$skills.push($k);
		});
		if (!$skills.length) {
			Share.hide();
			return Calculator.alert('Click share after making a skill build.');
		}
		Share.hide();
		$("#share").show();
		API.hit('share',{
			skills: $skills
		},function($d){
			if (!$d) {
				Share.hide();
				return Calculator.alert('Unable to generate link, try again later.');
			}
			if ($d.err) {
				Share.hide();
				return Calculator.alert($d.err);
			}
			if ($d.id) {
				$("#share textarea").val('https://atlas-calculator.firebaseapp.com/?ac_'+$d.id);
				$("#share .loading").hide();
				$("#share .valid").show();
			}
		});
	}
	, hide: function(){
		$("#share").hide();
		$("#share .loading").show();
		$("#share .valid").hide();
	}
	, ini: function(){
		if (typeof Share.bin == 'function') Share.bin();
	}
};
$(document).ready(Share.ini);

 const API = {
	que: []
	, hit: function($request,$data,$onsuccess,$onerror,$timeout) {
		if (!$data) $data = {};
		API.que.push([$request,$data,$onsuccess,$onerror,$timeout]);
	}
	, rip: function($request) {
		if (API.ripping) return; API.ripping = true;
		if (API.que) {
			var $_d = [];
			Object.keys(API.que).forEach(function($_i){
				var $_r = API.que[$_i];
				if ($_r[0] == $request) {
					$_d.push($_i);
				}
			});
			if ($_d.length) $_d.forEach(function($_i){
				if (API.que[$_i]) delete API.que[$_i];	
			});			
		}
		delete API.ripping;
	}
	, process: function(){
		if (API.ripping) return;
		if (!API.que.length) return;
		if (API.processing) return;
		var $r = API.que.shift();
		if (!$r) return API.process();
		API.last = $r;
		API.processing = true;
		if (typeof API.go == 'function') API.go($r[0],$r[1],$r[2],$r[3],$r[4]);
	}
	, go: function($request,$data,$onsuccess,$onerror,$timeout){
		var $urx = 'https://share.atlascalculator.com';
		if (!$timeout) var $timeout = 10000;
		$.ajax({
			url: $urx + '/?api='+$request,
			type: 'POST',
			data: $data,
			success: function(d,t,x){
				delete API.processing;
				if (d.throttle || d.bad_key) return API.que.unshift(API.last);
				if ($onsuccess) $onsuccess(d);
			},
			timeout: $timeout,
			error: function(d,t,x){
				API.processing = false;
				if ($onerror) $onerror(d);
			}
		});
	}
	, ini: function(){
		setInterval(API.process,800);
	}
};
$(document).ready(API.ini);

