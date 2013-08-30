<script type="text/javascript">
{literal}

    function confirm_delete(){    
      return confirm("Are you sure that you want delete all options?");
    }

{/literal}
{assign var=querystring value="reset=1&gid="|cat:$smarty.get.gid|cat:"&fid="|cat:$smarty.get.fid}

  url = '{crmURL p="civicrm/admin/custom/group/field/import" q=$querystring}';
  html = '<div class="action-link" style="margin-bottom:9px;"><a href="' + url + '" class="button"><span><div class="icon add-icon"></div> Import Options</span></a></div>';
  url_delete = '{crmURL p="civicrm/admin/custom/group/field/delete q=$querystring}';
  html_delete = '<div class="action-link" style="margin-bottom:9px;"><a href="' + url_delete + '" class="button" onclick="return confirm_delete()"><span><div class="icon add-icon"></div> Delete Options</span></a></div>';
  cj('#access').before(html);
  cj('#access').before(html_delete);
</script> 