<script id="widget-customization-tabs-template"
	type="text/x-handlebars-template">
<h2 class="nav-tab-wrapper">
	{{#each this}}
		<a href='#' class='nav-tab{{#if_eq @index 0}} nav-tab-active{{/if_eq}}' data-tab-key='{{@index}}'>{{tab}}</a>
	{{/each}}
</h2>
</script>

<script id="widget-customization-tab-default"
	type="text/x-handlebars-template">
<div id='content' class='left w50'>{{{links.[0].content}}}</div>

<div id='navigation' class='right w40'>
	{{#each links}}
		{{#if content_heading}}<h4>{{content_heading}}</h4>{{/if}}
		<li class='customization_widget_navigation' data-tab-key='0' data-link-key='{{@index}}'>{{#if icon}}<span class="dashicons {{icon}}"></span> {{/if}}{{{link}}}</li>
	{{/each}}
</div>

<div class='clear'></div>
</script>
