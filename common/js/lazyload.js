
!function(e,t,o,n){
	var i=e(t);
	e.fn.lazyload=function(r){
		function f(){
			var t=0;
			a.each(function(){
				var o=e(this);
				if(!d.skip_invisible||o.is(":visible"))if(e.abovethetop(this,d)||e.leftofbegin(this,d));
				else if(e.belowthefold(this,d)||e.rightoffold(this,d)){
					if(++t>d.failure_limit)return!1
				}
				else o.trigger("appear"),t=0
			})
		}
		var l,a=this,d={
			threshold:0,failure_limit:0,event:"scroll",effect:"show",container:t,data_attribute:"original",skip_invisible:!0,appear:null,load:null
		};
		return r&&(n!==r.failurelimit&&(r.failure_limit=r.failurelimit,delete r.failurelimit),n!==r.effectspeed&&(r.effect_speed=r.effectspeed,delete r.effectspeed),e.extend(d,r)),l=d.container===n||d.container===t?i:e(d.container),0===d.event.indexOf("scroll")&&l.bind(d.event,function(){
			return f()
		}),this.each(function(){
			var t=this,o=e(t);
			t.loaded=!1,o.one("appear",function(){
				if(!this.loaded){
					if(d.appear){
						var n=a.length;
						d.appear.call(t,n,d)
					}
					e("<img />").bind("load",function(){
						o.hide().attr("src",o.data(d.data_attribute))[d.effect](d.effect_speed),t.loaded=!0;
						var n=e.grep(a,function(e){
							return!e.loaded
						});
						if(a=e(n),d.load){
							var i=a.length;
							d.load.call(t,i,d)
						}
					}).attr("src",o.data(d.data_attribute))
				}
			}),0!==d.event.indexOf("scroll")&&o.bind(d.event,function(){
				t.loaded||o.trigger("appear")
			})
		}),i.bind("resize",function(){
			f()
		}),/iphone|ipod|ipad.*os 5/gi.test(navigator.appVersion)&&i.bind("pageshow",function(t){
			t.originalEvent&&t.originalEvent.persisted&&a.each(function(){
				e(this).trigger("appear")
			})
		}),e(o).ready(function(){
			f()
		}),this
	}
	,e.belowthefold=function(o,r){
		var f;
		return f=r.container===n||r.container===t?i.height()+i.scrollTop():e(r.container).offset().top+e(r.container).height(),f<=e(o).offset().top-r.threshold
	}
	,e.rightoffold=function(o,r){
		var f;
		return f=r.container===n||r.container===t?i.width()+i.scrollLeft():e(r.container).offset().left+e(r.container).width(),f<=e(o).offset().left-r.threshold
	}
	,e.abovethetop=function(o,r){
		var f;
		return f=r.container===n||r.container===t?i.scrollTop():e(r.container).offset().top,f>=e(o).offset().top+r.threshold+e(o).height()
	}
	,e.leftofbegin=function(o,r){
		var f;
		return f=r.container===n||r.container===t?i.scrollLeft():e(r.container).offset().left,f>=e(o).offset().left+r.threshold+e(o).width()
	}
	,e.inviewport=function(t,o){
		return!(e.rightoffold(t,o)||e.leftofbegin(t,o)||e.belowthefold(t,o)||e.abovethetop(t,o))
	}
	,e.extend(e.expr[":"],{
		"below-the-fold":function(t){
			return e.belowthefold(t,{
				threshold:0
			})
		}
		,"above-the-top":function(t){
			return!e.belowthefold(t,{
				threshold:0
			})
		}
		,"right-of-screen":function(t){
			return e.rightoffold(t,{
				threshold:0
			})
		}
		,"left-of-screen":function(t){
			return!e.rightoffold(t,{
				threshold:0
			})
		}
		,"in-viewport":function(t){
			return e.inviewport(t,{
				threshold:0
			})
		}
		,"above-the-fold":function(t){
			return!e.belowthefold(t,{
				threshold:0
			})
		}
		,"right-of-fold":function(t){
			return e.rightoffold(t,{
				threshold:0
			})
		}
		,"left-of-fold":function(t){
			return!e.rightoffold(t,{
				threshold:0
			})
		}
	})
}(jQuery,window,document);
