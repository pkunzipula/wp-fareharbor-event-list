<?php
/*
 * Plugin Name: J Caps Fareharbor Event List for Wordpress
 * Description: Drop a shortcode anywhere you want to display a list of Fareharbor Events by category!
 * Version: 1.0
 * Author: James Capra
 * Author URL: http://internechee.net
 */
  function make_event_list() {
    wp_enqueue_script('moment.min', plugin_dir_url( __FILE__ ) . 'js/moment.min.js');
    wp_enqueue_script('axios.min', plugin_dir_url( __FILE__ ) . 'js/axios.min.js');
    wp_enqueue_script('api-call', plugin_dir_url( __FILE__ ) . 'js/apiCall.js');
    wp_enqueue_style('event-list-style', plugin_dir_url( __FILE__ ) . 'css/event-list-style.css');
  ?>
      <ol id="js-itemList" class="hs-item-list">

      </ol>
    <script>
      (function() {
        var results = document.getElementById("js-itemList");
        var today = moment().format('YYYY-MM-DD');
        var twomonths = moment().add(45, 'days').format('YYYY-MM-DD');
        var itemID =  12101 //12146 //12101;
        axios({
          method: 'get',
          url: `https://cors-anywhere.herokuapp.com/https://fareharbor.com/api/external/v1/companies/greenvalleyrange/items/${itemID}/availabilities/date-range/${today}/${twomonths}/?api-app=f0e53a5f-48c1-446c-a0c1-01a21702f301&api-user=7171c7df-a7ab-409a-98cd-46c4268ae315`
        })
          .then(function (response) {
            var bookings = response.data.availabilities;
            bookings = bookings.slice(0,5);
          	bookings.forEach(function(el, i) {
              var itemName = el.item.name.split(":")[0];
              itemName.trim();
              var itemPrice = el.item.name.split(":")[1];
              itemPrice.trim();
              if ( itemPrice == "") {
                itemPrice = "Free!";
              }
              var itemTime = moment(el.start_at).format('h:mma') + '-' + moment(el.end_at).format('h:mma');
              var itemDay = moment(el.start_at).format('ddd, MMM Do');
              var itemBookit = "https://fareharbor.com/embeds/book/greenvalleyrange/items/"+itemID+"/availability/"+el.pk+"/book/";
              results.innerHTML += 
                `<li>
                  <div class="image" data-count="${i+1}" style="background-image: url(https://cdn.filestackcontent.com/nxIGXKw8QsOtFfZFWkT2);">
                  </div>
                  <div class="text">
                    <p><span class="day">${itemDay}</span>&nbsp;&bull;&nbsp;<span class="hours">${itemTime}</span></p>
                    <h4>${itemName}</h4>
                    <p><span class="price">${itemPrice}</span><span class="bookit"><a href="${itemBookit}">Book It!</a></span></p>
                  </div>
                </li>`;
            });
          })
          .catch(function (error) {
            results.innerHTML = 'NAH, No GOOD';
          });
      })();
    </script>
  <?php } 
  function make_event_list_init() {
    add_shortcode('jcaps_event_list', 'make_event_list');
  }
add_action('init', 'make_event_list_init');