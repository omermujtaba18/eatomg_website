



<footer id="footer" class="footer footer-layout1 text-center bg-dark">
    <div class="footer-inner">
        <div class="footer-bottom">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12">
                        <span>Copyright 2020 &copy;</span>
                    </div>
                </div><!-- /.row -->
            </div><!-- /.container -->
           
        </div><!-- /.Footer-bottom -->
    </div><!-- /.Footer-inner -->
</footer><!-- /.Footer -->
<button id="scrollTopBtn"><i class="fa fa-angle-up"></i></button>
<a href="/cart"><button id="checkoutBtn">Checkout <?= "(". $cart_total . ")"; ?></button></a>

</div>
<!-- /.wrapper -->

<script src="/js/plugins.js"></script>
<script src="/js/main.js"></script>

<script>
    setTimeout(() => {
        $('#err').alert('close')
    }, 3000);
</script>



</body>

</html>