from flask import Flask, request, render_template_string

app = Flask(__name__)
FLAG = open("/flag.txt").read()

BLACKLIST = [
    "os", "system", "eval", "exec", "class",
    "subprocess",  "read", "config", "self", "mro", "_", "base", "subclasses"
]


BASE_PAGE = """
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>What is on your mind again !!!?</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { padding-top: 40px; background-color: #f8f9fa; }
    .container { max-width: 600px; }
    textarea { font-family: monospace; }
  </style>
</head>
<body>
  <div class="container">
    <h1 class="mb-4"></h1>
    <p class="lead">Take your turn.</p>
    {% if result %}
      <div class="alert alert-info" role="alert">
        <h4 class="alert-heading">Result</h4>
        <pre>{{ result }}</pre>
      </div>
    {% elif error %}
      <div class="alert alert-danger" role="alert">
        <strong>Error:</strong> {{ error }}
      </div>
    {% endif %}
    <form method="GET" action="/">
      <div class="mb-3">
        <label for="template" class="form-label">Box</label>
        <textarea class="form-control" id="template" name="template" rows="6" placeholder="Write down there...">{{ request.args.get('template', '') }}</textarea>
      </div>
      <button type="submit" class="btn btn-primary">Submit</button>
    </form>
  </div>
  <!-- Bootstrap JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
"""

@app.route("/", methods=["GET"])
def index():
    # Get the template parameter from query string
    template_input = request.args.get("template")
    
    # If no template is provided, render the form only.
    if template_input is None or template_input.strip() == "":
        return render_template_string(BASE_PAGE, result=None, error=None)

    # Block blacklisted keywords (case-insensitive)
    for keyword in BLACKLIST:
        if keyword in template_input.lower():
            error_message = f"Forbidden keyword: {keyword}"
            return render_template_string(BASE_PAGE, result=None, error=error_message), 403

    try:
        # Render the provided template string and show its output in the GUI.
        rendered_result = render_template_string(template_input)
        return render_template_string(BASE_PAGE, result=rendered_result, error=None)
    except Exception as e:
        error_message = str(e)
        return render_template_string(BASE_PAGE, result=None, error=error_message)

if __name__ == "__main__":
    # Listen on port 80 and on all interfaces
    app.run(host="0.0.0.0", port=80)
