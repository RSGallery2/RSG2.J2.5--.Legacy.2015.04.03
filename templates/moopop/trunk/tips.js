window.addEvent('domready', function(){
var moopopTips = Tips.extend({
	
	position: function(element){
		var pos = element.getPosition();
		this.toolTip.setStyles({
			'left': pos.x + this.options.offsets.x,
			'top': pos.y - this.toolTip.offsetHeight - this.options.offsets.y
		});
	},
	build: function(el){
		el.$tmp.myTitle = el.title;
		el.removeAttribute('title');
		el.$tmp.myText = '<img src="'+el.rel+'" />';
		el.addEvent('mouseenter', function(event){
			this.start(el);
			if (!this.options.fixed) this.locate(event);
			else this.position(el);
		}.bind(this));
		if (!this.options.fixed) el.addEvent('mousemove', this.locate.bindWithEvent(this));
		var end = this.end.bind(this);
		el.addEvent('mouseleave', end);
		el.addEvent('trash', end);
	},
	start: function(el){
		this.wrapper.empty();
		if (el.$tmp.myTitle){
			this.title = new Element('span').inject(new Element('div', {'class': this.options.className + '-title'}).inject(this.wrapper)).setHTML(el.$tmp.myTitle);
		}
		if (el.$tmp.myText){
			this.text = new Element('span').inject(new Element('div', {'class': this.options.className + '-text'}).inject(this.wrapper)).setHTML(el.$tmp.myText);
		}
		$clear(this.timer);
		this.timer = this.show.delay(this.options.showDelay, this);
	}
});
	var list = $$('.rsg2-moopop .rsg2-moopop-thumb');

	list.each(function(element) {
		var tip = new moopopTips(element, {
			className: 'moopop',
			initialize:function(){
				this.fx = new Fx.Style(this.toolTip, 'opacity', {duration: 500, wait: false}).set(0);
			},
			onShow: function(toolTip) {
				this.fx.start(1);
			},
			onHide: function(toolTip) {
				this.fx.start(0);
			}
		});
	});

});
