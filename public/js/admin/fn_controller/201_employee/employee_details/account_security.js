function AccountSecurityHandler(tab,param)
{
    var d = function () {
        e.classList.toggle("d-none"),
          s.classList.toggle("d-none"),
          n.classList.toggle("d-none");
      },
      c = function () {
        o.classList.toggle("d-none"),
          a.classList.toggle("d-none"),
          i.classList.toggle("d-none");
      };
    let s = document.getElementById("kt_signin_email_button");
    let e = document.getElementById("kt_signin_email");
    let n = document.getElementById("kt_signin_email_edit") ;
    let o = document.getElementById("kt_signin_password");
    let i = document.getElementById("kt_signin_password_edit");
    let a = document.getElementById("kt_signin_password_button");
    let r = document.getElementById("kt_signin_cancel");
    let l = document.getElementById("kt_password_cancel");

    s.querySelector("button").addEventListener("click", function () {
        d();
    });

    a.querySelector("button").addEventListener("click", function () {
        c();
    })

    r.addEventListener("click", function () {
        d();
        loadActiveTab(tab);
    });
    l.addEventListener("click", function () {
        c();
    });

    $(_page).find('#kt_signin_change_password, #kt_signin_change_email').attr('action','/hris/admin/201_employee/employee_details/update');
    fvAccountSecurity(false,tab,param);

}
