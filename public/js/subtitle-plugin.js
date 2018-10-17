(function (global, factory) {
	typeof exports === 'object' && typeof module !== 'undefined' ? module.exports = factory(require('chart.js')) :
	typeof define === 'function' && define.amd ? define(['chart.js'], factory) :
	(global.PluginSubtitle = factory(global.Chart));
}(this, (function (Chart) { 'use strict';

Chart = Chart && Chart.hasOwnProperty('default') ? Chart['default'] : Chart;

var defaultOptions = {
  /**
   * is the title shown
   * @member {boolean} display
   * @default false
   */
  display: false,

  /**
   * Font size in px
   * @member {Number}
   * @default 12
   */
  fontSize: 12,

  /**
   * Font family for the title text.
   * @member {String} fontFamily
   * @default "'Helvetica Neue', 'Helvetica', 'Arial', sans-serif"	
   */
  fontFamily: "'Helvetica Neue', 'Helvetica', 'Arial', sans-serif",

  /**
   * Font color
   * @member {String} fontColor
   * @default '#999'
   */
  fontColor: '#999',

  /**
   * Font style
   * @member {String} 'fontStyle'
   * @default 'normal'
   */
  fontStyle: 'normal',

  /**
   * Subtitle text to display
   * @member {String}
   * @default ''
   */
  text: ''
};

var SubtitlePlugin = {
  id: 'chartJsPluginSubtitle',

  /**
  * plugin hook to draw the sub title
  * @param chart chartjs instance
  * @param easingValue animation function
  * @param options plugin options
  */
  beforeDraw: function beforeDraw(chart, easingValue, options) {
    options = Object.assign({}, defaultOptions, options);
    if (options.display) {
      var width = chart.chart.width;
      var ctx = chart.chart.ctx;
      ctx.restore();
      ctx.font = options.fontSize + 'px ' + options.fontFamily;
      ctx.textBaseline = 'middle';
      var text = options.text;
      var textX = Math.round((width - ctx.measureText(text).width) / 2);
      var textY = 31;
      ctx.fillText(text, textX, textY);
      ctx.save();
    }
  }
};

Chart.pluginService.register(SubtitlePlugin);

return SubtitlePlugin;

})));
