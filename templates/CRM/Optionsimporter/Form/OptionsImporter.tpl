
<div class="crm-block crm-form-block crm-import-datasource-form-block">
	<h3>{ts}Upload CSV File{/ts}</h3>
	<table class="form-layout">
		<tr>
			<td class="label">{$form.uploadFile.label}</td>
			<td>
				{$form.uploadFile.html}<br />
				<div class="description">{ts}File format must be comma-separated-values (CSV). File must be UTF8 encoded if it contains special characters (e.g. accented letters, etc.).{/ts}</div>
				{ts 1=$uploadSize}Maximum Upload File Size: %1 MB{/ts}
			</td>
		</tr>
		<tr>
			<td></td>
			<td>{$form.skipColumnHeader.html} {$form.skipColumnHeader.label}
				<div class="description">{ts}Check this box if the first row of your file consists of field names{/ts}</div>
			</td>
		</tr>
		<tr class="crm-import-datasource-form-block-fieldSeparator">
			<td class="label">{$form.fieldSeparator.label}</td>
			<td>{$form.fieldSeparator.html}</td>
		</tr>
		<tr class="crm-import-datasource-form-block-fieldSeparator">
			<td class="label">{$form.textEnclosure.label}</td>
			<td>{$form.textEnclosure.html}</td>
		</tr>		
		<tr class="crm-import-datasource-form-block-fieldSeparator">
             <td class="label">{$form.colOrder.label}</td>
             <td>{$form.colOrder.html}</td>
         </tr>
         <tr class="crm-import-datasource-form-block-fieldSeparator">
             <td class="label">{$form.overrideimport.label}</td>
             <td>{$form.overrideimport.html}</td>
         </tr>
	</table>

	<div class="crm-submit-buttons">{include file="CRM/common/formButtons.tpl" location="bottom"} </div>
</div>
{literal}
<script type="text/javascript"> 	
cj(document).ready(function(){
	cj('form:[name="OptionsImporter"]').submit(function(){
 		if(cj('input[id="uploadFile"]').val() == ""){
			{/literal}
			alert("{ts}You haven't selected any file to import.{/ts}");
			{literal}
			return false;
		}
 		{/literal}
 		return confirm("{ts}You are going to import the file into option values.\n\nAre you sure to proceed?{/ts}");
 		{literal}
 	});
});
</script>
{/literal}