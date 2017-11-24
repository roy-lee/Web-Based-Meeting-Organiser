<div class="col-sm-12">
					<p class="back-link">Lumino Theme by <a href="https://www.medialoot.com/">Medialoot</a></p>
				</div>
			</div><!--/.row-->
		</div>	<!--/.main-->

		<script src="js/jquery-1.11.1.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/chart.min.js"></script>
		<script src="js/chart-data.js"></script>
		<script src="js/easypiechart.js"></script>
		<script src="js/easypiechart-data.js"></script>
		<script src="js/bootstrap-table.js"></script>
		<script src="js/moment.js"></script>
		<script src="js/moment-with-locales.js"></script>
		<script src="js/bootstrap-datetimepicker.js"></script>
		<script src="js/jquery.validate.min.js"></script>
		<script src="js/createMeeting.js"></script>
		<script src="js/custom.js"></script>

		</script>
		<script>
			window.onload = function () {
				var chart1 = document.getElementById("line-chart").getContext("2d");
				window.myLine = new Chart(chart1).Line(lineChartData, {
				responsive: true,
				scaleLineColor: "rgba(0,0,0,.2)",
				scaleGridLineColor: "rgba(0,0,0,.05)",
				scaleFontColor: "#c5c7cc"
				});
			};
		</script>

		<script>
		var role = "<?php echo $role ?>"; // "A string here"
		  $(document).ready(function() {
		    if (role == "Participant") {
		      $('.organiserMenu').hide();
		    }
		    else {
		      $('.participantMenu').hide();
		    }
		    });
		</script>

	</body>

</html>
