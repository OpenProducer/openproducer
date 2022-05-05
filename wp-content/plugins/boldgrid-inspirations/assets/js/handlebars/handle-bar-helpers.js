Handlebars.registerHelper('toLowerCase', function(str) {
	return str.toLowerCase();
});

Handlebars.registerHelper('json', function(str) {
	return JSON.stringify(str);
});

Handlebars.registerHelper('if_eq', function(a, b, opts) {
	if (a == b)
		return opts.fn(this);
	else
		return opts.inverse(this);
});

Handlebars.registerHelper('objCount', function(obj) {
	// return str.toLowerCase();
	return Object.keys(obj).length;
});

Handlebars.registerHelper("getValueAtKey", function(object, key) {
	return object[key];
});

Handlebars.registerHelper("getValueAtKeyKey", function(object, key1, key2) {
	return object[key1][key2];
});

Handlebars.registerHelper("multiply", function(value, multiplier) {
	return parseInt(value) * parseInt(multiplier);
});

// http://www.levihackwith.com/creating-new-conditionals-in-handlebars/
// Usage: {{#ifCond var1 '==' var2}}
Handlebars.registerHelper('ifCond', function(v1, operator, v2, options) {
	switch (operator) {
	case '==':
		return (v1 == v2) ? options.fn(this) : options.inverse(this);
	case '===':
		return (v1 === v2) ? options.fn(this) : options.inverse(this);
	case '<':
		return (v1 < v2) ? options.fn(this) : options.inverse(this);
	case '<=':
		return (v1 <= v2) ? options.fn(this) : options.inverse(this);
	case '>':
		return (v1 > v2) ? options.fn(this) : options.inverse(this);
	case '>=':
		return (v1 >= v2) ? options.fn(this) : options.inverse(this);
	case '&&':
		return (v1 && v2) ? options.fn(this) : options.inverse(this);
	case '||':
		return (v1 || v2) ? options.fn(this) : options.inverse(this);
	default:
		return options.inverse(this);
	}
});

// Determine if a variable is set and not null.
// Only supports strings at this time.
Handlebars.registerHelper('isSetAndNotNull', function(a, options) {
	var mytype = typeof a;

	switch (mytype) {
	case 'string':
		if ('' != a.trim()) {
			return options.fn(this);
		} else {
			return options.inverse(this);
		}
		break;
	default:
		// Return false by default.
		return options.inverse(this);
	}
});