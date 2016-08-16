<!-- INCLUDE header.tpl -->
<p>« <a href="${RETURN_BACK_LINK}">${RETURN_BACK}</a></p>


<!-- IF '${INFO_ALERT}' != '' -->
<div class="alert alert-info"><span class="icon icon-exclamation-sign"></span> ${INFO_ALERT} </div>
<!-- END IF -->
<!-- IF '${ERROR_ALERT}' != '' -->
<div class="alert alert-error">
  <button class="close" data-dismiss="alert">×</button>
  <strong>${STR_ERROR}!</strong> ${ERROR_ALERT} </div>
<!-- END IF -->
<!-- BEGIN show_errors -->
<div class="alert alert-error"> <a class="close" href="#" data-dismiss="alert">×</a>
  <h4 class="alert-heading">${STR_IDENTIFIED_FOLLOWING_ERRORS}:</h4>
  <ul>
    <!-- BEGIN row -->
    <li> ${ERROR}</li>
    <!-- END row -->
  </ul>
</div>
<!-- END show_errors -->

<form method="POST" action="${ACTION}">
<p>* - ${STR_REQUIRED_FIELDS}</p>

<div class="form-group">
<label for="login">${STR_LOGIN}*</label>
<input class="form-control" type="text" name="login" value="${USER_LOGIN}">
</div>

<div class="form-group">
<label for="password">${STR_PASSWORD}*</label>
<input class="form-control" type="password" name="password">
</div>

<div class="form-group">
<label for="password">${STR_PASSWORD_AGAIN}*</label>
<input class="form-control" type="password" name="password_again">
</div>

<div class="form-group">
  <label for="status">${STR_ROLE}*</label>
  <select for="status" name="user_role" class="form-control form-primary">
	 <option value="admin" <!-- IF '${USER_ROLE}' == 'admin' -->selected="selected"<!-- END IF -->>${STR_ADMIN}</option>
	 <option value="moderator"  <!-- IF '${USER_ROLE}' == 'moderator' -->selected="selected"<!-- END IF -->>${STR_MODERATOR}</option>
     <option value="editor"  <!-- IF '${USER_ROLE}' == 'editor' -->selected="selected"<!-- END IF -->>${STR_EDITOR}</option>
   </select>
</div>

<input type="submit" class="btn btn-success" name="action" value="${BUTTON}">
</form>

<!-- INCLUDE footer.tpl -->