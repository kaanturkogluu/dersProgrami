<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $student->full_name }} - Program Takvimi</title>
    <style>
        @charset "UTF-8";
        * {
            font-family: 'DejaVu Sans', sans-serif;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 2px;
            background: white;
            direction: ltr;
        }
        
        .header {
            text-align: center;
            margin-bottom: 3px;
            border-bottom: 1px solid #333;
            padding-bottom: 2px;
        }
        
        .header h1 {
            font-size: 18px;
            margin: 0;
            color: #333;
        }
        
        .header h2 {
            font-size: 14px;
            margin: 1px 0;
            color: #666;
        }
        
        .student-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
            font-size: 12px;
        }
        
        .calendar-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 2px;
            table-layout: fixed;
        }
        
        .calendar-table th {
            background-color: #4472C4;
            color: white;
            padding: 3px 1px;
            text-align: center;
            font-size: 12px;
            border: 1px solid #333;
            font-weight: bold;
            width: 14.28%;
        }
        
        .calendar-table td {
            border: 1px solid #333;
            padding: 0px;
            vertical-align: top;
            height: 35px;
            font-size: 12px;
            background-color: white;
            width: 14.28%;
            overflow: hidden;
        }
        
        .calendar-table tr {
            height: 35px;
        }
        
        .day-header {
            font-weight: bold;
        }
        
        .program-item {
            background-color: #E7F3FF;
            border: 1px solid #B4D7FF;
            padding: 0px;
            margin-bottom: 0px;
            font-size: 12px;
            border-radius: 0px;
            line-height: 1.0;
        }
        
        .program-item.completed {
            background-color: #D5E8D4;
            border-color: #82B366;
        }
        
        .course-name {
            font-weight: bold;
            color: #1F4E79;
            font-size: 12px;
            line-height: 1.0;
            margin: 0;
            padding: 0;
        }
        
        .topic-name {
            color: #2F5597;
            font-size: 11px;
            line-height: 1.0;
            margin: 0;
            padding: 0;
        }
        
        .subtopic-name {
            color: #5B9BD5;
            font-size: 10px;
            line-height: 1.0;
            margin: 0;
            padding: 0;
        }
        
        .notes {
            color: #666;
            font-size: 10px;
            margin: 0;
            padding: 0;
            line-height: 1.0;
        }
        
        .footer {
            margin-top: 5px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ccc;
            padding-top: 2px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $student->full_name }} - Program Takvimi</h1>
        <h2>{{ $student->student_number }} - Haftalık Program Görünümü</h2>
    </div>
    
    <div class="student-info">
        <div>
            <strong>Öğrenci:</strong> {{ $student->full_name }}<br>
            <strong>Numara:</strong> {{ $student->student_number }}
        </div>
        <div>
            <strong>Alanlar:</strong> 
            @foreach($schedules->pluck('areas')->flatten()->unique() as $area)
                {{ $area }}{{ !$loop->last ? ', ' : '' }}
            @endforeach
        </div>
        <div>
            <strong>Rapor Tarihi:</strong> {{ date('d.m.Y H:i') }}
        </div>
    </div>
    
    @if($weeklySchedule->count() > 0)
    <table class="calendar-table">
        <thead>
            <tr>
                @php
                    $days = [
                        'monday' => 'Pazartesi',
                        'tuesday' => 'Salı', 
                        'wednesday' => 'Çarşamba',
                        'thursday' => 'Perşembe',
                        'friday' => 'Cuma',
                        'saturday' => 'Cumartesi',
                        'sunday' => 'Pazar'
                    ];
                @endphp
                
                @foreach($days as $dayKey => $dayName)
                    <th class="day-header">
                        {{ $dayName }}
                        @if($schedules->count() > 0)
                            @php
                                $firstSchedule = $schedules->first();
                                $startDate = $firstSchedule->start_date;
                                $dayIndex = array_search($dayKey, array_keys($days));
                                $dayDate = $startDate->copy()->addDays($dayIndex);
                            @endphp
                            <br><small>{{ $dayDate->format('d.m') }}</small>
                        @endif
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @php
                // En fazla program olan günü bul
                $maxPrograms = 0;
                foreach($days as $dayKey => $dayName) {
                    if(isset($weeklySchedule[$dayKey])) {
                        $maxPrograms = max($maxPrograms, $weeklySchedule[$dayKey]->count());
                    }
                }
            @endphp
            
            @for($row = 0; $row < $maxPrograms; $row++)
                <tr>
                    @foreach($days as $dayKey => $dayName)
                        <td>
                            @if(isset($weeklySchedule[$dayKey]) && isset($weeklySchedule[$dayKey][$row]))
                                @php $program = $weeklySchedule[$dayKey][$row]; @endphp
                                
                                <div class="program-item {{ $program['is_completed'] ? 'completed' : '' }}">
                                    <div class="course-name">{{ $program['course']->name }}</div>
                                    @if($program['topic'])
                                        <div class="topic-name">{{ $program['topic']->name }}</div>
                                    @endif
                                    @if($program['subtopic'])
                                        <div class="subtopic-name">{{ $program['subtopic']->name }}</div>
                                    @endif
                                    @if($program['notes'])
                                        <div class="notes">{{ $program['notes'] }}</div>
                                    @endif
                                    @if($program['is_completed'])
                                        <div class="notes">✓</div>
                                    @endif
                                </div>
                            @endif
                        </td>
                    @endforeach
                </tr>
            @endfor
        </tbody>
    </table>
    @else
    <div style="text-align: center; padding: 40px; color: #666;">
        <h3>Henüz program oluşturulmamış</h3>
        <p>Bu öğrenci için henüz haftalık program bulunmuyor.</p>
    </div>
    @endif
    
    <div class="footer">
        <p>Rapor: {{ date('d.m.Y H:i') }} | Toplam: {{ $schedules->count() }} program, {{ $weeklySchedule->flatten()->count() }} ders</p>
    </div>
</body>
</html>
