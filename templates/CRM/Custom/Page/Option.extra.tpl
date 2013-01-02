<script type="text/javascript"> 
	{assign var=querystring value="reset=1&gid="|cat:$smarty.get.gid|cat:"&fid="|cat:$smarty.get.fid}
	
	url = '{crmURL p="civicrm/admin/custom/group/field/import" q=$querystring}';	
	html = '<div class="action-link"><a href="' + url + '" class="button"><span><div class="icon add-icon"></div> Import Options</span></a></div>';

	cj('#access').before(html);
</script> 