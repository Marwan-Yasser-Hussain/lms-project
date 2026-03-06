<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    /**
     * Show the visual certificate editor.
     */
    public function edit(Course $course)
    {
        return view('admin.certificates.editor', compact('course'));
    }

    /**
     * Save certificate template settings (background image + name position).
     */
    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'certificate_bg_image'      => 'nullable|image|max:5120', // up to 5 MB
            'certificate_name_x'        => 'required|integer|min:0',
            'certificate_name_y'        => 'required|integer|min:0',
            'certificate_name_font_size'=> 'required|integer|min:10|max:200',
            'certificate_name_color'    => 'required|string|max:20',
            'certificate_name_font'     => 'required|string|max:100',
            'remove_bg'                 => 'nullable|boolean',
        ]);

        // Handle background image upload
        if ($request->hasFile('certificate_bg_image')) {
            $validated['certificate_bg_image'] = $request->file('certificate_bg_image')
                ->store('courses/certificates', 'public');
        } elseif ($request->boolean('remove_bg')) {
            $validated['certificate_bg_image'] = null;
        } else {
            unset($validated['certificate_bg_image']);
        }

        unset($validated['remove_bg']);

        $course->update($validated);

        return redirect()
            ->route('admin.courses.certificate.edit', $course)
            ->with('success', 'Certificate template saved successfully!');
    }
}
