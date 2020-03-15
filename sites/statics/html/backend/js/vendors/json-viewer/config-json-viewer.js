<script>
    var jsonObj = {};
    var jsonViewer = new JSONViewer();
    document.querySelector("#json").appendChild(jsonViewer.getContainer());
    var value_obj = JSON.stringify({"call":"line_item","params":{"marketplace_id":18,"changed_within":86400,"filter":"end_date gt 2020-02-19 00:00:00"}});
    jsonObj = JSON.parse(value_obj);                
    var loadJsonBtn = document.querySelector("button.load-json");
		        
    loadJsonBtn.addEventListener("click", function() {                    
        jsonViewer.showJSON(jsonObj);
    });
</script>