/* Javascript for Admin page functionalities.
 * 
 * Clear All Reports
 * 
 * 
 * 
 * 
*/


function admin() {
	
	this.initialization = function () {
		
	}
	
	this.clearAllReports = function() {
		
		
		$('#clearReports').click(function() {
			
			$.ajax({
				type: "GET",
				url: "http://projetlibre/admin/clear",
				data: formData,
				success: function(data){
					console.log('Success, reports cleared');
				},
				error: function(result, status, error){
					console.log('error reports');
				}
			});
			
		});
		
	};
}