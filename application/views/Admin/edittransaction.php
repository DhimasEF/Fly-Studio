<div class="content">   
	<div class="bg justify-between align-center">
		<h1 class="f3">
			<a class="no-deco" href="<?= base_url('Admin/transaction') ?>"><i class='bx bx-transfer'></i>Edit Transaction</a> /
			<span class="text2"> <?= $transaction->id_transaction; ?> 
			</span>
		</h1>
    </a>
	</div>
	
<div class="mt-2">
	<form method="post" class="trans-edit">
		<div class="form-group">
			<label for="file_url" title="File URL"><i class='bx bx-link-alt'></i></label>
			<input type="text" name="file_url" id="file_url" value="<?= htmlspecialchars($transaction->order_file_url); ?>" required>
		</div>

		<div class="form-group">
			<label for="total_price" title="Total Harga"><i class='bx bx-money'></i></label>
			<input type="number" name="total_price" id="total_price" value="<?= htmlspecialchars($transaction->total_price); ?>" required>
		</div>

		<div class="form-group">
			<label for="order_status" title="Status Transaksi"><i class='bx bx-transfer'></i></label>
			<select name="order_status" id="order_status" required>
				<option value="waiting" <?= $transaction->order_status == 'pending' ? 'selected' : '' ?>>Menunggu</option>
				<option value="in_progress" <?= $transaction->order_status == 'in_progress' ? 'selected' : '' ?>>Diproses</option>
				<option value="completed" <?= $transaction->order_status == 'completed' ? 'selected' : '' ?>>Selesai</option>
				<option value="cancelled" <?= $transaction->order_status == 'cancelled' ? 'selected' : '' ?>>Dibatalkan</option>
			</select>
		</div>

		<div class="form-group password-wrapper">
			<label for="password" title="Password"><i class='bx bx-lock-alt'></i></label>
			<input type="password" name="password" id="password" value="<?= htmlspecialchars($transaction->password); ?>" required>
			<i class='bx bx-show toggle-eye' id="togglePassword"></i>
		</div>

		<button type="submit"><i class='bx bx-save'></i> Update</button>
	</form>

</div>

<script>
    const toggle = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');

    toggle.addEventListener('click', () => {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);

        toggle.classList.toggle('bx-show');
        toggle.classList.toggle('bx-hide');
    });
</script>


