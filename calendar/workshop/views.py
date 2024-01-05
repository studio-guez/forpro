from django.shortcuts import render


def workshop_list(request):
    return render(request, 'workshop-list.html')

def workshop_detail(request):
    return render(request, 'workshop-list.html')