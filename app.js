const express = require("express");

const app = express();

const getClientIp = (req) => {
  const ipAddress = req.headers["x-forwarded-for"] || req.connection.remoteAddress;
  return ipAddress.includes("::ffff:") ? ipAddress.split(":").pop() : ipAddress;
};

app.use((req, res, next) => {
  console.log(req.method, req.url, getClientIp(req));
  next();
});

app.get("/api/hello", async (req, res) => {
  const { visitor_name } = req.query;
  const user_ip = getClientIp(req);

  // get location from ip
  const location_api_url = `http://ip-api.com/json/${user_ip}`;
  const location_response = await fetch(location_api_url);

  const location_response_json = await location_response.json();
  const user_city = location_response_json.city;
  const user_longitude = location_response_json.lon;
  const user_latitude = location_response_json.lat;

  // get weather report from location
  const weather_api_url = `https://api.open-meteo.com/v1/forecast?latitude=${user_latitude}&longitude=${user_longitude}&current=temperature_2m`;
  const weather_response = await fetch(weather_api_url);
  const weather_response_json = await weather_response.json();
  const location_temperature = weather_response_json.current.temperature_2m;

  return res.json({
    client_ip: user_ip,
    location: user_city,
    greeting: `Hello, ${visitor_name}!, the temperature is ${location_temperature} degrees Celcius in ${user_city}`,
  });
});

app.listen(3000, () => {
  console.log("Server STarted");
});
