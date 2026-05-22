#!/usr/bin/env python3
from flask import Flask, jsonify
from flask_basicauth import BasicAuth
from functools import wraps
import json
import os

app = Flask(__name__)

# Basic authentication credentials
app.config['BASIC_AUTH_USERNAME'] = 'toto'
app.config['BASIC_AUTH_PASSWORD'] = 'python'
app.config['BASIC_AUTH_FORCE'] = False

basic_auth = BasicAuth(app)

# Path to the JSON data file (mounted as a volume)
STUDENT_FILE = '/data/student_age.json'


# ── Helper: return the configured password ────────────────────────────────────
def get_password(username):
    """Return the password for a given username, or None if unknown."""
    users = {
        app.config['BASIC_AUTH_USERNAME']: app.config['BASIC_AUTH_PASSWORD']
    }
    return users.get(username)


# ── Helper: 401 Unauthorized response ────────────────────────────────────────
def unauthorized():
    """Return a 401 Unauthorized JSON response."""
    return jsonify({'error': 'Unauthorized', 'message': 'Valid credentials are required'}), 401


# ── Routes ────────────────────────────────────────────────────────────────────
@app.route('/pozos/api/v1.0/get_student_ages', methods=['GET'])
@basic_auth.required
def get_student_ages():
    if not os.path.exists(STUDENT_FILE):
        return jsonify({'error': 'Data file not found'}), 404

    with open(STUDENT_FILE, 'r') as f:
        data = json.load(f)

    return jsonify(data)


@app.errorhandler(401)
def handle_unauthorized(e):
    return unauthorized()


if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000, debug=True)