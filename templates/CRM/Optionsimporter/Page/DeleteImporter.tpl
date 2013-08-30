<h3>{ts}Options deleted{/ts} ({$number_elements_deleted})</h3>
<table class="form-layout">       
  <tr>        
      <th> Value </th>
      <th> Label </th>
    </tr>
       {foreach key=key item=item from=$value_deleted}
      <tr>
          <td class="crm-optionsimporter-deletedvalue">{$key}</td> 
          <td class="crm-optionsimporter-deletedlabel">{$item}</td> 
      </tr>
      {/foreach}
</table><br />
<h3>{ts}Options not deleted{/ts} ({$number_elements_not_deleted})</h3>
<table class="form-layout">
  <tr>
      <th> Value </th>
      <th> Label </th>
    </tr>
      {foreach key=key item=item from=$value_not_deleted}
      <tr>
          <td class="crm-optionsimporter-notdeletedvalue">{$key}</td> 
          <td class="crm-optionsimporter-notdeletedlabel">{$item}</td> 
      </tr>
      {/foreach}
</table>