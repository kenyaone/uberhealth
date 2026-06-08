#!/bin/bash
# Start UberHealth development servers

echo "Starting UberHealth backend..."
cd /home/tele/uberhealth/backend
venv/bin/python manage.py runserver 8000 &
BACKEND_PID=$!

echo "Starting UberHealth frontend..."
cd /home/tele/uberhealth/frontend
npm run dev &
FRONTEND_PID=$!

echo ""
echo "=========================================="
echo " UberHealth is running!"
echo " Backend:  http://localhost:8000"
echo " Frontend: http://localhost:5173"
echo " Admin:    http://localhost:8000/admin"
echo "           (admin / admin1234)"
echo "=========================================="
echo ""
echo "Press Ctrl+C to stop all servers."

trap "kill $BACKEND_PID $FRONTEND_PID 2>/dev/null; exit" INT TERM
wait
