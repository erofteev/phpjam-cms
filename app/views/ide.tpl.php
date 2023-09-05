<?php
ob_start();
?>

<link href="assets/css/graphiql.min.css" rel="stylesheet"/>
<script src="assets/js/graphiql/react.production.min.js"></script>
<script src="assets/js/graphiql/react-dom.production.min.js"></script>
<script src="assets/js/graphiql/graphiql.min.js"></script>



<script>
  window.onload = function() {
    function graphQLFetcher(graphQLParams) {
      return fetch('api/graphql', {
        method: 'post',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
        body: JSON.stringify(graphQLParams),
      })
        .then(function (response) {
          return response.json().catch(function () {
            return response.text();
          });
        })
        .catch(function (error) {
          return error;
        });
    }

    ReactDOM.render(
      React.createElement(GraphiQL, { fetcher: graphQLFetcher }),
      document.getElementById('graphiql')
    );
  }
  </script>

<?php
$inline_head = ob_get_clean();
ob_start();
?>


<?php require VIEWS . '/includes/header.php' ?>

  <!--<link href="https://unpkg.com/graphiql/graphiql.min.css" rel="stylesheet"/>-->
  <!--<script src="https://unpkg.com/react/umd/react.production.min.js"></script>-->
  <!--<script src="https://unpkg.com/react-dom/umd/react-dom.production.min.js"></script>-->
  <!--<script src="https://unpkg.com/graphiql/graphiql.min.js"></script>-->

<!--<style>
  .graphiql-container {
    border-radius: 4px;
  }
</style>-->

  <div id="graphiql"></div>


<?php require VIEWS . '/includes/footer.php' ?>
