jQuery,wp.customize.bind("ready",function(){wp.customize("primary_color",function(i){wp.customize.control("primary_color_hue",function(o){function n(){"custom"===i.get()?o.container.slideDown(180):o.container.slideUp(180)}n(),i.bind(n)})})});