# Docker Mini Project — POZOS Student List

A fully containerized web application built for **POZOS**, a French IT company.  
The app displays a list of students with their ages using a decoupled Docker architecture.

---

## Architecture

```
Browser → PHP/Apache (website) → Flask API (api) → student_age.json
```

| Service | Technology | Port |
|---|---|---|
| API | Python 3.13 + Flask | 5000 |
| Website | PHP 8 + Apache | 80 |

Both services run on an isolated Docker bridge network (`pozos_network`).  
The PHP container calls the API **server-side** via cURL — the browser never contacts the API directly.

---

## Project Structure

```
docker-mini-project/
├── docker-compose.yml
├── api/
│   ├── Dockerfile
│   ├── student_age.py       # Flask REST API
│   ├── student_age.json     # Student data
│   └── requirements.txt
└── website/
    └── index.php            # PHP frontend
```

---

## Getting Started

### Prerequisites

- [Docker](https://docs.docker.com/get-docker/) installed
- [Docker Compose](https://docs.docker.com/compose/install/) installed

### Run the project

```bash
git clone https://github.com/ourdjiniaymen/docker-mini-project.git
cd docker-mini-project
docker-compose up --build -d
```

Then open your browser at **http://localhost** and click **List Students**.

### Stop the project

```bash
docker-compose down
```

---

## Configuration

All credentials and the API URL are injected via environment variables in `docker-compose.yml` — nothing is hardcoded in the source files.

| Variable | Value | Description |
|---|---|---|
| `API_URL` | `http://api:5000/pozos/api/v1.0/get_student_ages` | Internal API endpoint |
| `USERNAME` | `toto` | Basic auth username |
| `PASSWORD` | `python` | Basic auth password |

---

## API

**Endpoint:** `GET /pozos/api/v1.0/get_student_ages`  
**Auth:** HTTP Basic (`toto` / `python`)

```bash
curl -u toto:python http://localhost:5000/pozos/api/v1.0/get_student_ages
```

**Response:**
```json
{
  "students": [
    { "name": "Alice", "age": 17 },
    { "name": "Bob",   "age": 16 }
  ]
}
```

---

## Private Docker Registry

A private registry and its web UI can be deployed alongside the app:

```bash
# Start the registry
docker run -d -p 5001:5000 --name registry --restart=always registry:2

# Start the registry UI
docker run -d -p 8080:80 --name registry-ui \
  -e REGISTRY_URL=http://registry:5001 \
  --link registry joxit/docker-registry-ui:latest

# Tag and push the API image
docker tag student_list_api:latest localhost:5001/student_list_api:latest
docker push localhost:5001/student_list_api:latest
```

Registry UI available at **http://localhost:8080**

---

## Author

**Aymen Ourdjini** — DevOps TP 2025/2026