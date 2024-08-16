# Road Trip Planner App
This project is a simple road trip planner application built with PHP, MySQL, Leaflet, and OpenStreetMap. It allows users to create a list of destinations, reorder them, and visualize the route on a map.

**Prerequisites:**
- PHP: Version 8.2 or higher
- MySQL: A MySQL database server
- Composer: A dependency manager for PHP
- Web Server: XAMPP, MAMP, Laragon, or a similar local server environment.


### Installation
1. **Clone the repository:**
```bash
git clone https://github.com/gilbertozioma/Road-Trip-Planner.git
```

2. **Navigate to the project directory:**
```bash
cd Road-Trip-Planner
```

3. **Install dependencies:**
```bash
composer install
```
```bash
npm install
```
4. **Configure database:**
Create a new database in your MySQL server and name it "road_trip_planner".
Update the database credentials in the `.env` file:
```bash
DB_HOST=localhost
DB_DATABASE=road_trip_planner
DB_USERNAME=root
DB_PASSWORD=
```

6. **Run database migrations:**
```bash
php artisan migrate
```

## Running the Application

**Start your web server:**
- If using XAMPP, start the Apache and MySQL services.
- If using MAMP, start the MAMP server.
- If using Laragon, start the Laragon server.
```bash
php artisan serve
```
```bash
npm run dev
```

**Access the application:**
Open your web browser and navigate to `http://127.0.0.1:8000` or the address of your local server.

## Usage
1. **Register in the application**

2. **Add destinations:**
- Enter a destination in the "Add Destination" field and click "Add".

3. **Reorder destinations:**
- Drag and drop destinations in the "Destinations" list to change their order.

4. **View map:**
- The map shows your destinations.

5. **Trip Summary**
- The calculated time and distance on every two consecutive destination.


## Features
- User authentication: Allow users to register in the application and save their trips and access them later.
- Add destinations: Users can add destinations to their trip.
- Reorder destinations: Users can rearrange the order of destinations.
- Map visualization: The application displays the route on a map using Leaflet and OpenStreetMap.
- User trip data stores in the browser's local storage.
- Responsive design: The application is designed to be responsive and work on different screen sizes.

### Thank you. 🙂