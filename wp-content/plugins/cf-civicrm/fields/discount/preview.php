<div class="preview-caldera-config-group">
	{{#unless hide_label}}<lable class="control-label">{{label}}{{#if required}} <span style="color:#ff0000;">*</span>{{/if}}</lable>{{/unless}}
	<div class="preview-caldera-config-field">
		<input {{#if hide_label}}placeholder="{{label}}"{{else}}placeholder="{{config/placeholder}}"{{/if}} type="text" class="preview-field-config" value="{{config/default}}">
		<button class="button" disabled="disabled">Apply discount</button>
		<span class="help-block">{{caption}}</span>
	</div>
</div>