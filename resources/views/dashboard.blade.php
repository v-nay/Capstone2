<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
            <style>
table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

td, th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
}

/* tr:nth-child(even) {
  background-color: #dddddd;
} */
</style>
        </h2>
    </x-slot>

    <button type="button" style="background-color: #4CAF50; color: white; padding: 10px 20px; border: none; border-radius: 5px; font-size: 16px; cursor: pointer; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); transition: background-color 0.3s ease;">
    Generate Report
</button>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <table>

  <tr>
    <th>Motel</th>
    <th>Contact</th>
    <th>postal code</th>
    <th>website</th>
    <th>score</th>
    <th>rank</th>
    <th>rating</th>
    <th> user rating</th>
    <th>Lat</th>
    <th>lng</th>
    <th>photos</th>


    

  </tr>
  <tr>
    <td>Sunset Motel</td>
    <td>123-456-7890</td>
    <td>12345</td>
    <td>www.sunsetmotel.com</td>
    <td>85</td>
    <td>3</td>
    <td>4.2</td>
    <td>4.5</td>
    <td>34.0522</td>
    <td>-118.2437</td>
    <td><img src="photo1.jpg" alt="Sunset Motel"></td>
</tr>
<tr>
    <td>Beachside Inn</td>
    <td>987-654-3210</td>
    <td>67890</td>
    <td>www.beachsideinn.com</td>
    <td>90</td>
    <td>1</td>
    <td>4.8</td>
    <td>4.9</td>
    <td>36.7783</td>
    <td>-119.4179</td>
    <td><img src="photo2.jpg" alt="Beachside Inn"></td>
</tr>
<tr>
    <td>Mountain Lodge</td>
    <td>234-567-8901</td>
    <td>11223</td>
    <td>www.mountainlodge.com</td>
    <td>75</td>
    <td>5</td>
    <td>3.9</td>
    <td>4.0</td>
    <td>39.7392</td>
    <td>-104.9903</td>
    <td><img src="photo3.jpg" alt="Mountain Lodge"></td>
</tr>
<tr>
    <td>City Stay Motel</td>
    <td>345-678-9012</td>
    <td>44556</td>
    <td>www.citystaymotel.com</td>
    <td>80</td>
    <td>4</td>
    <td>4.0</td>
    <td>4.1</td>
    <td>40.7128</td>
    <td>-74.0060</td>
    <td><img src="photo4.jpg" alt="City Stay Motel"></td>
</tr>
<tr>
    <td>Parkview Lodge</td>
    <td>456-789-0123</td>
    <td>66778</td>
    <td>www.parkviewlodge.com</td>
    <td>95</td>
    <td>2</td>
    <td>4.7</td>
    <td>4.8</td>
    <td>41.8781</td>
    <td>-87.6298</td>
    <td><img src="photo5.jpg" alt="Parkview Lodge"></td>
</tr>
<tr>
    <td>Coastal Motel</td>
    <td>567-890-1234</td>
    <td>22334</td>
    <td>www.coastalmotel.com</td>
    <td>70</td>
    <td>7</td>
    <td>3.5</td>
    <td>3.6</td>
    <td>33.8688</td>
    <td>-118.2279</td>
    <td><img src="photo6.jpg" alt="Coastal Motel"></td>
</tr>
<tr>
    <td>Riverside Motel</td>
    <td>678-901-2345</td>
    <td>88900</td>
    <td>www.riversidemotel.com</td>
    <td>85</td>
    <td>6</td>
    <td>4.3</td>
    <td>4.4</td>
    <td>32.7157</td>
    <td>-117.1611</td>
    <td><img src="photo7.jpg" alt="Riverside Motel"></td>
</tr>
<tr>
    <td>Oasis Motel</td>
    <td>789-012-3456</td>
    <td>55667</td>
    <td>www.oasismotel.com</td>
    <td>65</td>
    <td>9</td>
    <td>3.2</td>
    <td>3.3</td>
    <td>38.8895</td>
    <td>-77.0353</td>
    <td><img src="photo8.jpg" alt="Oasis Motel"></td>
</tr>
<tr>
    <td>Lakeside Motel</td>
    <td>890-123-4567</td>
    <td>33445</td>
    <td>www.lakesidemotel.com</td>
    <td>78</td>
    <td>8</td>
    <td>4.0</td>
    <td>4.2</td>
    <td>37.7749</td>
    <td>-122.4194</td>
    <td><img src="photo9.jpg" alt="Lakeside Motel"></td>
</tr>
<tr>
    <td>Desert Inn</td>
    <td>901-234-5678</td>
    <td>44567</td>
    <td>www.desertinn.com</td>
    <td>88</td>
    <td>10</td>
    <td>4.5</td>
    <td>4.6</td>
    <td>36.1699</td>
    <td>-115.1398</td>
    <td><img src="photo10.jpg" alt="Desert Inn"></td>
</tr>

</table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
