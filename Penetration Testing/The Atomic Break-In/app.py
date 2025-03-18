from flask import Flask, request, redirect, make_response, render_template
import hmac
import hashlib

app = Flask(__name__)

SECRET_KEY = "supersecretkey"

def create_signed_cookie(role):
    data = f"role={role}"
    signature = hmac.new(SECRET_KEY.encode(), data.encode(), hashlib.sha256).hexdigest()
    return f"{data}|{signature}"

def verify_signed_cookie(cookie):
    try:
        data, signature = cookie.split("|")
        expected_signature = hmac.new(SECRET_KEY.encode(), data.encode(), hashlib.sha256).hexdigest()
        if hmac.compare_digest(signature, expected_signature):
            return data.split("=")[1]
    except:
        pass
    return None

@app.route("/")
def home():
    auth_cookie = request.cookies.get("auth")
    role = verify_signed_cookie(auth_cookie)
    if role:
        return render_template("index.html", authenticated=True, role=role.capitalize(), message="Welcome to your dashboard!")
    return render_template("index.html", authenticated=False)

@app.route("/login", methods=["POST"])
def login():
    username = request.form.get("username")
    password = request.form.get("password")

    if username and password:
        role = "user"  
        if username == "admin" and password == "admin123":  
            role = "admin"
        cookie = create_signed_cookie(role)
        response = make_response(redirect("/dashboard"))
        response.set_cookie("auth", cookie)
        return response
    return redirect("/")

@app.route("/dashboard")
def dashboard():
    auth_cookie = request.cookies.get("auth")
    role = verify_signed_cookie(auth_cookie)
    if not role:
        return redirect("/")
    if role == "admin":
        return render_template(
            "index.html",
            authenticated=True,
            role="Admin",
            message="You have access to the admin panel.",
            flag="CATXCSC{I_am_ATOMIC_;)}" 
        )
    return render_template(
        "index.html",
        authenticated=True,
        role="User",
        message="You are a regular user."
    )

if __name__ == "__main__":
    app.run(debug=True, port=6969)