from flask import Flask, request, render_template
import subprocess
import os

app = Flask(__name__)

@app.route('/', methods=['GET', 'POST'])
def index():
    result = ""
    code = ""
    
    if request.method == 'POST':
        code = request.form.get('code', '')
        
        # Save the code to a temporary file
        with open('temp_code.py', 'w') as f:
            f.write(code)
        
        # Execute the code
        try:
            output = subprocess.check_output(['python3', 'temp_code.py'], 
                                            stderr=subprocess.STDOUT,
                                            timeout=5)
            result = output.decode('utf-8')
        except subprocess.CalledProcessError as e:
            result = e.output.decode('utf-8')
        except Exception as e:
            result = str(e)
            
        # Clean up
        if os.path.exists('temp_code.py'):
            os.remove('temp_code.py')
    
    return render_template('index.html', code=code, result=result)

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000)