<div class="SivForm WebUser">
    <div class="SivFormHeader">
        <h2>SetEditorAction</h2>
        <div class="SivFormDescription">
            
        </div>
    </div>
    <div class="SivFormBody">
         <form method="post" data-dojo-type="dijit/form/Form" data-dojo-attach-point='mainForm'>
            <div class="SivFormContents">
                <div class="SivFieldContainer">
                    <div class="SivFieldContainerTitle"></div>
                    <div class="SivFieldContainerDescription"></div>                
                    <table cellpadding=0 cellspacing=0>
                        <tbody data-dojo-attach-point='tableNode'>
						<tr>
							<td class="SivFormLabel">SectionEditors</td>
							<td class="SivFormInput">
				<div data-dojo-type="Siviglia/forms/inputs/RelationMxN" data-dojo-attach-point="SectionEditors" Wdata-dojo-inputParams='{"LABEL":["name","state","isHome","caching","cacheTime","path","creationDate"],"VALUE":["id_seccion"]}' data-dojo-definition='{"MODEL":"WebSection\\SectionEditors","FIELD":"editor","REMOTE_MODEL":"WebSection","ROLE":"HAS_MANY","MULTIPLICTY":"M:N","CARDINALITY":100,"UNIQUE_RELATIONS":1}'>
							</td>
						</tr>
                 </tbody>            
            </table>
            </div>
            <div class="SivFormButtons"></div>
        </div>
    </form>
</div>