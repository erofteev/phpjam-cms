<?php
session_start();
if (isset($_SESSION['user_id'])): ?>

        </div>
      </div>
<?php endif; ?>

    </main>
    <footer class="footer">
      <p>CMS ver. 1.2</p>
      Author by <a href="https://erofteev.github.io" target="blank">Erofteev</a>
    </footer>
  </div>

  <script>
    (()=>{"use strict";!function(){const e=document.querySelector(".sidebar"),t=document.querySelector(".content"),s=document.querySelector(".sidebar__toggle");let a="true"===localStorage.getItem("isCollapsed");window.innerWidth<=767&&null===a&&(a=!0),a&&(e.classList.add("collapsed"),t.classList.add("expanded")),window.addEventListener("load",(()=>{e.classList.remove("no-transition"),t.classList.remove("no-transition")})),s.addEventListener("click",(()=>{a?(e.classList.remove("collapsed"),t.classList.remove("expanded")):(e.classList.add("collapsed"),t.classList.add("expanded")),a=!a,localStorage.setItem("isCollapsed",a)})),document.querySelectorAll(".sidebar__item .sidebar__link").forEach((e=>{e.pathname===window.location.pathname&&e.parentElement.classList.add("active")}))}()})();
  </script>

</body>
</html>