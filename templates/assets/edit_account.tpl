<!-- INCLUDE header.tpl -->
<p>« <a href="${RETURN_BACK_LINK}">${RETURN_BACK}</a></p>
<!-- INCLUDE info.tpl -->
<!-- INCLUDE errors.tpl -->
<!-- INCLUDE success.tpl -->
<form method="POST" action="${ACTION}">
<input type="hidden" name="id" value="${ID}">
<p>* - ${STR_REQUIRED_FIELDS}</p>

<div class="form-group">
<label for="password">${STR_PASSWORD}*</label>
<input class="form-control" type="password" name="password">
</div>

<div class="form-group">
<label for="password_again">${STR_PASSWORD_AGAIN}*</label>
<input class="form-control" type="password" name="password_again">
</div>

<div class="form-group">
  <label for="status">${STR_ROLE}*</label>
  <select for="status" name="status" class="form-control form-primary">
	 <option value="admin" <!-- IF '${USER_ROLE}' == 'admin' -->selected="selected"<!-- END IF -->>${STR_ADMIN}</option>
	 <option value="moderator"  <!-- IF '${USER_ROLE}' == 'moderator' -->selected="selected"<!-- END IF -->>${STR_MODERATOR}</option>
     <option value="editor"  <!-- IF '${USER_ROLE}' == 'editor' -->selected="selected"<!-- END IF -->>${STR_EDITOR}</option>
   </select>
</div>

<input type="submit" class="btn btn-success" name="action" value="${BUTTON}">
</form>
<!-- INCLUDE footer.tpl -->