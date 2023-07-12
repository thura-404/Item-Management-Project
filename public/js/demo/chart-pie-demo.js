// Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#858796';

// Declare an async function to get the data and create the pie chart
async function createPieChart() {
  try {
    // Use await with $.ajax to get the active items
    let activeData = await $.ajax({
      url: "/items/getActive",
      type: "GET",
      dataType: "json"
    });
    // Get the number of active items
    let activeItems = activeData.length;

    // Use await with $.ajax to get the inactive items
    let inactiveData = await $.ajax({
      url: "/items/getInactive",
      type: "GET",
      dataType: "json"
    });
    // Get the number of inactive items
    let inactiveItems = inactiveData.length;

    // Get the context for the pie chart
    var ctx = document.getElementById("myPieChart");
    // Create the pie chart using Chart.js
    var myPieChart = new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels: ["Inactive", "Active"],
        datasets: [{
          data: [inactiveItems, activeItems],
          backgroundColor: ['#4e73df', '#1cc88a'],
          hoverBackgroundColor: ['#2e59d9', '#17a673'],
          hoverBorderColor: "rgba(234, 236, 244, 1)",
        }],
      },
      options: {
        maintainAspectRatio: false,
        tooltips: {
          backgroundColor: "rgb(255,255,255)",
          bodyFontColor: "#858796",
          borderColor: '#dddfeb',
          borderWidth: 1,
          xPadding: 15,
          yPadding: 15,
          displayColors: false,
          caretPadding: 10,
        },
        legend: {
          display: false
        },
        cutoutPercentage: 80,
      },
    });
  } catch (error) {
    // Handle any errors from the $.ajax functions
    console.log('error');
  }
}

// Call the async function
createPieChart();






// // Set new default font family and font color to mimic Bootstrap's default styling
// Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
// Chart.defaults.global.defaultFontColor = '#858796';

// var activeItems = 1;
// var inactiveItems = 1;

// $.ajax({
//   url: "/items/getActive",
//   type: "GET",
//   dataType: "json",
//   success: function (data) {
//     activeItems = data.length;
//     console.log(activeItems);
//     $.ajax({
//       url: "/items/getInactive",
//       type: "GET",
//       dataType: "json",
//       success: function (data) {
//         inactiveItems = data.length;
//         console.log(inactiveItems);
//       },
//       error: function () {
//         console.log('error');
//       }
//     });
//   },
//   error: function () {
//     console.log('error');
//   }
// });

// console.log(activeItems);
// console.log(inactiveItems);


// // Pie Chart Example
// var ctx = document.getElementById("myPieChart");
// var myPieChart = new Chart(ctx, {
//   type: 'doughnut',
//   data: {
//     labels: ["Inactive", "Active"],
//     datasets: [{
//       data: [activeItems, inactiveItems],
//       backgroundColor: ['#4e73df', '#1cc88a'],
//       hoverBackgroundColor: ['#2e59d9', '#17a673'],
//       hoverBorderColor: "rgba(234, 236, 244, 1)",
//     }],
//   },
//   options: {
//     maintainAspectRatio: false,
//     tooltips: {
//       backgroundColor: "rgb(255,255,255)",
//       bodyFontColor: "#858796",
//       borderColor: '#dddfeb',
//       borderWidth: 1,
//       xPadding: 15,
//       yPadding: 15,
//       displayColors: false,
//       caretPadding: 10,
//     },
//     legend: {
//       display: false
//     },
//     cutoutPercentage: 80,
//   },
// });