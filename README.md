# Simple Docker / Native PHP + Vanilla JS Image Uploader

A simple, secure, and modern web application for uploading and managing images, featuring a premium design with full edit and delete functionality.

## Prerequisites

Before setting up the project, you must have **Docker** and **Docker Compose** installed on your system.

### 1. Install Docker

#### **macOS**
1. Download **Docker Desktop for Mac** from [Docker Hub](https://www.docker.com/products/docker-desktop).
2. Open the `.dmg` file and drag Docker to your Applications folder.
3. Launch Docker from your Applications.

#### **Windows**
1. Ensure you have **WSL2** enabled.
2. Download **Docker Desktop for Windows** from [Docker Hub](https://www.docker.com/products/docker-desktop).
3. Run the installer and follow the prompts.
4. Ensure "Use the WSL 2 based engine" is checked during installation.

#### **Linux (Ubuntu/Debian)**
1. Update your package index:
   ```bash
   sudo apt-get update
   ```
2. Install Docker:
   ```bash
   sudo apt-get install docker.io
   ```
3. Install Docker Compose:
   ```bash
   sudo apt-get install docker-compose-v2
   ```
4. Start and enable Docker:
   ```bash
   sudo systemctl enable --now docker
   ```

---

## 2. Installation & Setup

1. **Clone the repository**:
   ```bash
   git clone https://bitbucket.org/arthur_abogadil/corporate-keys-image-upload.git
   cd corporate-keys
   ```

2. **Start the application**:
   Run the following command in the root directory:
   ```bash
   docker-compose up -d
   ```
   This will build the containers and start the web server (PHP/Nginx) and the MySQL database.

3. **Access the application**:
   Open your browser and navigate to:
   [http://localhost:8080](http://localhost:8080)

---

## 3. Useful Commands

### Check Database Status
Verify if the database and table were created correctly:
```bash
docker exec -it mysql_db mysql -u corporate_keys -pcorporate_keys corporate_keys -e "Describe images"
```

### Stop the Application
To stop and remove the containers:
```bash
docker-compose down
```

### Reset the Database
To reset the database (delete all data and volumes):
```bash
docker-compose down -v
```

### View Logs
To troubleshoot any issues:
```bash
docker-compose logs -f
```

---

## Features
- **Modern UI**: Clean, responsive layout with Inter typography.
- **Secure Uploads**: Automated folder permission checks and XSS protection.
- **Edit Functionality**: Change image titles instantly via AJAX.
- **Robust Connection**: PDO-based database interactions with optimized error handling.
