<h3>{ts}Options not deleted{/ts}</h3>
<table class="form-layout">
  <tr>
      <th> Value </th>
      <th> Label </th>
    </tr>
      {foreach key=key item=item from=$value_not_deleted}
      <tr>
          <td class="value">{$key}</td> 
          <td class="label">{$item}</td> 
      </tr>
      {/foreach}
</table>
<h3>{ts}Options deleted{/ts}</h3>
<table class="form-layout">       
  <tr>        
      <th> Value </th>
      <th> Label </th>
    </tr>
       {foreach key=key item=item from=$value_deleted}
      <tr>
          <td class="value">{$key}</td> 
          <td class="label">{$item}</td> 
      </tr>
      {/foreach}
</table>
<div>Has been deleted: {$number_elements_deleted} items.</div>
<div>Has not been deleted: {$number_elements_not_deleted} items.</div>