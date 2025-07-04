/********************************************
 * REVOLUTION 5.3 EXTENSION - ACTIONS
 * @version: 1.3.2 (12.04.2016)
 * @requires jquery.themepunch.revolution.js
 * @author ThemePunch
*********************************************/

(function($) {

var _R = jQuery.fn.revolution,
	_ISM = _R.is_mobile();

///////////////////////////////////////////
// 	EXTENDED FUNCTIONS AVAILABLE GLOBAL  //
///////////////////////////////////////////
jQuery.extend(true,_R, {
	checkActions : function(_nc,opt,as) {
		checkActions_intern(_nc,opt,as);
	}		
});

//////////////////////////////////////////
//	-	INITIALISATION OF ACTIONS 	-	//
//////////////////////////////////////////
var checkActions_intern = function(_nc,opt,as) {

if (as)				
	jQuery.each(as,function(i,a) {		

		a.delay = parseInt(a.delay,0)/1000;
		_nc.addClass("noSwipe");

		// LISTEN TO ESC TO EXIT FROM FULLSCREEN
		if (!opt.fullscreen_esclistener) {
			if (a.action=="exitfullscreen" || a.action=="togglefullscreen") {				
				jQuery(document).keyup(function(e) {
				     if (e.keyCode == 27 && jQuery('#rs-go-fullscreen').length>0)  
				     	_nc.trigger(a.event);				   
				});
				opt.fullscreen_esclistener = true;
			}
		}

		var tnc = a.layer == "backgroundvideo" ? jQuery(".rs-background-video-layer") : a.layer == "firstvideo" ? jQuery(".tp-revslider-slidesli").find('.tp-videolayer') : jQuery("#"+a.layer);

		// NO NEED EXTRA TOGGLE CLASS HANDLING
		if (jQuery.inArray(a.action,["toggleslider","toggle_mute_video","toggle_global_mute_video","togglefullscreen"])!=-1) {
			_nc.data('togglelisteners',true);
		}

		// COLLECT ALL TOGGLE TRIGGER TO CONNECT THEM WITH TRIGGERED LAYER
		switch (a.action) {
			case "togglevideo":
				jQuery.each(tnc,function(i,_tnc) {
					_tnc = jQuery(_tnc);
					var videotoggledby = _tnc.data('videotoggledby');
					if (videotoggledby == undefined)
						videotoggledby = new Array();
					videotoggledby.push(_nc);					
					_tnc.data('videotoggledby',videotoggledby)				
				});
			break;
			case "togglelayer":
				jQuery.each(tnc,function(i,_tnc) {
					_tnc = jQuery(_tnc);
					var layertoggledby = _tnc.data('layertoggledby');
					if (layertoggledby == undefined)
						layertoggledby = new Array();
					layertoggledby.push(_nc);					
					_tnc.data('layertoggledby',layertoggledby)				
				});
			break;
			case "toggle_mute_video":
				jQuery.each(tnc,function(i,_tnc) {
					_tnc = jQuery(_tnc);
					var videomutetoggledby = _tnc.data('videomutetoggledby');
					if (videomutetoggledby == undefined)
						videomutetoggledby = new Array();
					videomutetoggledby.push(_nc);					
					_tnc.data('videomutetoggledby',videomutetoggledby);				
				});
			break;
			case "toggle_global_mute_video":
				jQuery.each(tnc,function(i,_tnc) {
					_tnc = jQuery(_tnc);
					var videomutetoggledby = _tnc.data('videomutetoggledby');
					if (videomutetoggledby == undefined)
						videomutetoggledby = new Array();
					videomutetoggledby.push(_nc);					
					_tnc.data('videomutetoggledby',videomutetoggledby);				
				});
			break;
			case "toggleslider":
				if (opt.slidertoggledby == undefined) opt.slidertoggledby = new Array();
					opt.slidertoggledby.push(_nc);
			break;
			case "togglefullscreen":								
				if (opt.fullscreentoggledby == undefined) opt.fullscreentoggledby = new Array();
				opt.fullscreentoggledby.push(_nc);													
			break;

		}
		
		_nc.on(a.event,function() {		

			var tnc = a.layer == "backgroundvideo" ? jQuery(".active-revslide .slotholder .rs-background-video-layer") : a.layer == "firstvideo" ? jQuery(".active-revslide .tp-videolayer").first() : jQuery("#"+a.layer);

			if (a.action=="stoplayer" || a.action=="togglelayer" || a.action=="startlayer") {
				if (tnc.length>0) 												
					if (a.action=="startlayer" || (a.action=="togglelayer" && tnc.data('animdirection')!="in")) {
						tnc.data('animdirection',"in");
						var otl = tnc.data('timeline_out'),
							base_offsetx = opt.sliderType==="carousel" ? 0 : opt.width/2 - (opt.gridwidth[opt.curWinRange]*opt.bw)/2,
							base_offsety=0;																		
						if (otl!=undefined) otl.pause(0).kill();																		
						if (_R.animateSingleCaption) _R.animateSingleCaption(tnc,opt,base_offsetx,base_offsety,0,false,true);	
						var tl = tnc.data('timeline');
						tnc.data('triggerstate',"on");		
						_R.toggleState(tnc.data('layertoggledby'));																												
						punchgs.TweenLite.delayedCall(a.delay,function() {
							tl.play(0);
						},[tl]);								
					} else 

					if (a.action=="stoplayer" || (a.action=="togglelayer" && tnc.data('animdirection')!="out")) {
						tnc.data('animdirection',"out");
						tnc.data('triggered',true);
						tnc.data('triggerstate',"off");
						if (_R.stopVideo) _R.stopVideo(tnc,opt);
						if (_R.endMoveCaption)												
							punchgs.TweenLite.delayedCall(a.delay,_R.endMoveCaption,[tnc,null,null,opt]);
						_R.unToggleState(tnc.data('layertoggledby'))														
					}															
			} else 	{
				if (_ISM && (a.action=='playvideo' || a.action=='stopvideo' || a.action=='togglevideo' || a.action=='mutevideo' || a.action=='unmutevideo' || a.action=='toggle_mute_video' || a.action=='toggle_global_mute_video')) {
						actionSwitches(tnc,opt,a,_nc);
				} else {
					punchgs.TweenLite.delayedCall(a.delay,function() {
						actionSwitches(tnc,opt,a,_nc);	
					},[tnc,opt,a,_nc]);
				}
			}
		});		
		switch (a.action) {					
			case "togglelayer":
			case "startlayer":
			case "playlayer":
			case "stoplayer":
				var tnc = jQuery("#"+a.layer);		
					if (tnc.data('start')!="bytrigger")	{
						tnc.data('triggerstate',"on");						
						tnc.data('animdirection',"in");						
					}	
			break;
		}
	})				
}


var actionSwitches = function(tnc,opt,a,_nc) {
	switch (a.action) {
		case "scrollbelow":		

			_nc.addClass("tp-scrollbelowslider");
			_nc.data('scrolloffset',a.offset);
			_nc.data('scrolldelay',a.delay);						
			var off=getOffContH(opt.fullScreenOffsetContainer) || 0,
				aof = parseInt(a.offset,0) || 0;									 
			off =  off - aof || 0;							
			jQuery('body,html').animate({scrollTop:(opt.c.offset().top+(jQuery(opt.li[0]).height())-off)+"px"},{duration:400});																											
		break;
		case "callback":
			eval(a.callback);							
		break;
		case "jumptoslide":	
			switch (a.slide.toLowerCase()) {
				case "+1":
				case "next":
					opt.sc_indicator="arrow";
					_R.callingNewSlide(opt,opt.c,1);					
				break;
				case "previous":
				case "prev":
				case "-1":									
					opt.sc_indicator="arrow";
					_R.callingNewSlide(opt,opt.c,-1);																		
				break;
				default:
					var ts = jQuery.isNumeric(a.slide) ?  parseInt(a.slide,0) : a.slide;
					_R.callingNewSlide(opt,opt.c,ts);									
				break;
			}												
		break;
		case "simplelink":						
			window.open(a.url,a.target);
		break;
		case "toggleslider":
			opt.noloopanymore=0;								
			if (opt.sliderstatus=="playing") {
				opt.c.revpause();
				opt.forcepause_viatoggle = true;
				_R.unToggleState(opt.slidertoggledby);
			}
			else {
				opt.forcepause_viatoggle = false;
				opt.c.revresume();	
				_R.toggleState(opt.slidertoggledby);							
			}
		break;
		case "pauseslider":								
			opt.c.revpause();	
			_R.unToggleState(opt.slidertoggledby);						
		break;
		case "playslider":			
			opt.noloopanymore=0;					
			opt.c.revresume();	
			_R.toggleState(opt.slidertoggledby);				
		break;
		case "playvideo":		
			
			if (tnc.length>0)													
				_R.playVideo(tnc,opt);									
		break;
		case "stopvideo":						
			if (tnc.length>0)										
				if (_R.stopVideo) _R.stopVideo(tnc,opt);									
		break;
		case "togglevideo":
			if (tnc.length>0) 										
				if (!_R.isVideoPlaying(tnc,opt))
					_R.playVideo(tnc,opt);
				else
					if (_R.stopVideo) _R.stopVideo(tnc,opt);		
		break;
		case "mutevideo":							
			if (tnc.length>0)									
				_R.muteVideo(tnc,opt);									
		break;
		case "unmutevideo":						
			if (tnc.length>0)										
				if (_R.unMuteVideo) _R.unMuteVideo(tnc,opt);									
		break;
		case "toggle_mute_video":
			
			if (tnc.length>0) 		
				if (_R.isVideoMuted(tnc,opt)) {
					_R.unMuteVideo(tnc,opt);			
				} else {
					if (_R.muteVideo) _R.muteVideo(tnc,opt);					
				}
			_nc.toggleClass('rs-toggle-content-active');
		break;
		case "toggle_global_mute_video":
		    if (_nc.hasClass("rs-toggle-content-active")) {
		    	opt.globalmute = false;				    	
		    	if (opt.playingvideos != undefined && opt.playingvideos.length>0) {			
					jQuery.each(opt.playingvideos,function(i,_nc) {							
						if (_R.unMuteVideo) _R.unMuteVideo(_nc,opt);
					});
				}
			 	
		    } else {
		    	opt.globalmute = true;			    	
		    	if (opt.playingvideos != undefined && opt.playingvideos.length>0) {			
					jQuery.each(opt.playingvideos,function(i,_nc) {									
						if (_R.muteVideo) _R.muteVideo(_nc,opt);
					});
				}			 	
		    }			
			_nc.toggleClass('rs-toggle-content-active');
		break;
		case "simulateclick":
			if (tnc.length>0) tnc.click();										
		break;
		case "toggleclass":
			if (tnc.length>0) 								
				if (!tnc.hasClass(a.classname))
					tnc.addClass(a.classname);
				else
					tnc.removeClass(a.classname);									
		break;
		case "gofullscreen":
		case "exitfullscreen":
		case "togglefullscreen":
			
			if (jQuery('#rs-go-fullscreen').length>0 && (a.action=="togglefullscreen" || a.action=="exitfullscreen")) {
				jQuery('#rs-go-fullscreen').appendTo(jQuery('#rs-was-here'));
				var paw = opt.c.closest('.forcefullwidth_wrapper_tp_banner').length>0 ? opt.c.closest('.forcefullwidth_wrapper_tp_banner') : opt.c.closest('.rev_slider_wrapper');
				paw.unwrap();
				paw.unwrap();
				opt.minHeight  = opt.oldminheight;
				opt.infullscreenmode = false;
				opt.c.revredraw();	
				if (opt.playingvideos != undefined && opt.playingvideos.length>0) {			
					jQuery.each(opt.playingvideos,function(i,_nc) {									
						_R.playVideo(_nc,opt);
					});
				}
				_R.unToggleState(opt.fullscreentoggledby);

			} else 
			if (jQuery('#rs-go-fullscreen').length==0 && (a.action=="togglefullscreen" || a.action=="gofullscreen")) {
				var paw = opt.c.closest('.forcefullwidth_wrapper_tp_banner').length>0 ? opt.c.closest('.forcefullwidth_wrapper_tp_banner') : opt.c.closest('.rev_slider_wrapper');
				paw.wrap('<div id="rs-was-here"><div id="rs-go-fullscreen"></div></div>');
				var gf = jQuery('#rs-go-fullscreen');
				gf.appendTo(jQuery('body'));
				gf.css({position:'fixed',width:'100%',height:'100%',top:'0px',left:'0px',zIndex:'9999999',background:'var(--white)fff'});
				opt.oldminheight = opt.minHeight;
				opt.minHeight = jQuery(window).height();							
				opt.infullscreenmode = true;
				opt.c.revredraw();	
				if (opt.playingvideos != undefined && opt.playingvideos.length>0) {			
					jQuery.each(opt.playingvideos,function(i,_nc) {									
						_R.playVideo(_nc,opt);
					});
				}	
				_R.toggleState(opt.fullscreentoggledby);						
			}	
			
		break;
		default:
			var obj = {};
			obj.event = a;
			obj.layer = _nc;			
			opt.c.trigger('layeraction',[obj]);
		break;
	}
}

var getOffContH = function(c) {
	if (c==undefined) return 0;		
	if (c.split(',').length>1) {
		oc = c.split(",");
		var a =0;
		if (oc)
			jQuery.each(oc,function(index,sc) {
				if (jQuery(sc).length>0)
					a = a + jQuery(sc).outerHeight(true);							
			});
		return a;
	} else {
		return jQuery(c).height();
	}
	return 0;
}

})(jQuery);