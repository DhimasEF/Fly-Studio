<div class="pd-container">
	<div class="signin-container">
		<div class="signin-card" id="signin-card">

			<!-- Front: Login -->
			<div class="side front" style="background-image: url('<?= base_url("assets/resource/banner.jpg") ?>');">
				<div class="overlay"></div>
				<div class="content">
					<h2>Login</h2>
					<form action="<?= base_url('signin/login'); ?>" method="post">
						<div class="input-group">
							<i class='bx bx-envelope left-icon'></i>
							<input type="email" name="email" placeholder="Email" required>
						</div>
						<div class="input-group password-group">
							<i class='bx bx-lock left-icon'></i>
							<input type="password" name="password" placeholder="Password" id="login-password" required>
							<i class='bx bx-hide toggle-password' onclick="togglePassword('login-password', this)"></i>
						</div>
						<button class="btn-signin" type="submit">Login</button>
					</form>
					<span class="toggle-link" onclick="flipCard()">Don't have an account? Register here</span>
				</div>
			</div>

			<!-- Back: Register -->
			<div class="side back" style="background-image: url('<?= base_url("assets/resource/banner2.jpg") ?>');">
				<div class="overlay"></div>
				<div class="content">
					<h2>Register</h2>
					<form action="<?= base_url('signin/register'); ?>" method="post">
						<div class="input-group">
							<i class='bx bx-user left-icon'></i>
							<input type="text" name="name" placeholder="Full Name" required>
						</div>
						<div class="input-group">
							<i class='bx bx-envelope left-icon'></i>
							<input type="email" name="email" placeholder="Email" required>
						</div>
						<div class="input-group password-group">
							<i class='bx bx-lock left-icon'></i>
							<input type="password" name="password" placeholder="Password" id="register-password" required>
							<i class='bx bx-hide toggle-password' onclick="togglePassword('register-password', this)"></i>
						</div>
						<button class="btn-signin" type="submit">Register</button>
					</form>
					<span class="toggle-link" onclick="flipCard()">Already have an account? Login here</span>
				</div>
			</div>

		</div>
	</div>
</div>

<!-- JS: Flip Card + Toggle Password -->
<script>
	function flipCard() {
		document.getElementById('signin-card').classList.toggle('flipped');
	}

	function togglePassword(id, icon) {
		const input = document.getElementById(id);
		if (input.type === "password") {
			input.type = "text";
			icon.classList.remove("bx-hide");
			icon.classList.add("bx-show");
		} else {
			input.type = "password";
			icon.classList.remove("bx-show");
			icon.classList.add("bx-hide");
		}
	}
</script>